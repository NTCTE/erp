<?php

namespace App\Http\Controllers\EdPart\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Legacy\Schedule\FullList;
use Illuminate\Support\Facades\Storage;
use Shuchkin\SimpleXLSX;

/**
 * LegacyController - контроллер API для расписания в старом режиме. Адаптировано под API.
 */
class LegacyController extends Controller
{
    /**
     * Получить информацию о расписании по пути /api/legacy/{date}.
     */
    public function getSchedule($date) {
        $schedule = FullList::where('date_at', $date) -> first();
        if (!empty($schedule)) {
            $path = $schedule
                -> files()
                -> orderByDesc('updated_at')
                -> first()
                -> url;
            $path = Storage::path("{$path['disk']}/{$path['path']}{$path['name']}.{$path['extension']}");
            return [
                'date' => $schedule['date_at'],
                'countOfChanges' => $schedule
                    -> files
                    -> count(),
                'lastChange' => $schedule
                    -> files
                    -> max('updated_at'),
                'data' => $this -> parseXLSX(
                    $path,
                    false
                ),
            ];
        }
        else
            return response() -> json([
                'message' => 'Schedule on this date is not found'
            ], 404);
    }
    
    /**
     * Получить список расписания по преподавателям, либо по группам, либо всем массивом.
     */
    public function getList($date, $type = '', $name = '') {
        $schedule = FullList::where('date_at', $date) -> first();
        if (!empty($schedule)) {
            $path = $schedule
                -> files()
                -> orderByDesc('updated_at')
                -> first()
                -> url;
            $path = Storage::path("{$path['disk']}/{$path['path']}{$path['name']}.{$path['extension']}");
            switch ($type) {
                case 'teacher':
                    if (!empty($name)) {
                        return $this -> get_teacher(
                            $name,
                            $this -> parseXLSX($path)
                        );
                    } else return response() -> json([
                        'message' => 'Name can\'t be empty!',
                    ], 404);
                break;
                case 'group':
                    if (!empty($name)) {
                        return $this -> get_group(
                            $name,
                            $this -> parseXLSX($path)
                        );
                    } else return response() -> json([
                        'message' => 'Name can\'t be empty!',
                    ], 404);
                break;
                case 'all':
                    return $this -> parseXLSX($path);
                default:
                    return response() -> json([
                        'message' => 'Type can be only: \'teacher\', \'group\' and \'all\''
                    ], 404);
            }
        } else
            return response() -> json([
                'message' => 'Schedule on this date is not found'
            ], 404);
    }

    /**
     * Парсер XLSX-таблицы по дате.
     */
    private function parseXLSX($path, $onlySchedule = true) {
        $xlsx = SimpleXLSX::parse($path);
        $return = [];
        if (!$onlySchedule)
            $return = [
                'teachers' => [],
                'groups' => [],
            ];

        for ($cntSheet = 0; $cntSheet < count($xlsx -> sheetNames()); $cntSheet++) {
            $sheet = $xlsx -> rows($cntSheet);
            if ($onlySchedule) {
                $return[$cntSheet] = [
                    'building' => [
                        'number' => $sheet[1][1],
                        'address' => $sheet[1][2],
                    ],
                    'schedule' => [],
                ];
            }
            $cursor = [
                'row' => 0,
                'col' => 6,
                'isFirst' => true,
            ];
            for ($cntGlobRow = 0; $cntGlobRow < 9; $cntGlobRow++) {
                $globColCnt = $cursor['isFirst'] ? 2 : 4;
                for ($cntGlobCol = 0; $cntGlobCol < $globColCnt; $cntGlobCol++) {
                    if (!empty($sheet[$cursor['row']][$cursor['col']])) {
                        if ($onlySchedule)
                            $group = [
                                'name' => $sheet[$cursor['row']][$cursor['col']],
                                'schedule' => [],
                            ];
                        else
                            $return['groups'][] = $sheet[$cursor['row']][$cursor['col']];
                        for ($cntLesson = 2; $cntLesson <= 8; $cntLesson++) {
                            if (!empty($sheet[$cursor['row'] + $cntLesson][$cursor['col'] + 1])) {
                                if ($onlySchedule) {
                                    $group['schedule'][] = [
                                        'lesson' => $sheet[$cursor['row'] + $cntLesson][$cursor['col']],
                                        'name' => explode(' (', $sheet[$cursor['row'] + $cntLesson][$cursor['col'] + 1])[0],
                                        'teachers' => (function() use ($sheet, $cursor, $cntLesson) {
                                            $raw = explode(' (', $sheet[$cursor['row'] + $cntLesson][$cursor['col'] + 1]);
                                            if (!empty($raw[1])) {
                                                $raw = mb_substr($raw[1], 0, mb_strlen($raw[1]) - 1);
                                                $raw = explode('/', $raw);
                                                return $raw;
                                            } else return [];
                                        })(),
                                        'rooms' => explode('/', strval($sheet[$cursor['row'] + $cntLesson][$cursor['col'] + 2])),
                                    ];
                                } else {
                                    $return['teachers'] = array_merge($return['teachers'], (function() use ($sheet, $cursor, $cntLesson) {
                                        $raw = explode(' (', $sheet[$cursor['row'] + $cntLesson][$cursor['col'] + 1]);
                                        if (!empty($raw[1])) {
                                            $raw = mb_substr($raw[1], 0, mb_strlen($raw[1]) - 1);
                                            $raw = explode('/', $raw);
                                            return $raw;
                                        } else return [];
                                    })());
                                }
                            }
                        }
                        if ($onlySchedule)
                            $return[$cntSheet]['schedule'][] = $group;
                    }
                    $cursor['col'] += 3;
                }
                if ($cursor['isFirst'])
                    $cursor['isFirst'] = false;
                $cursor['row'] += 9;
                $cursor['col'] = 0;
            }
        }
        if (!$onlySchedule) {
            $return['teachers'] = (function() use ($return) {
                $ret = [];
                foreach (array_unique($return['teachers']) as $value)
                    $ret[] = $value;
                return $ret;
            })();
            sort($return['teachers']);
            sort($return['groups']);
        }
        return $return;
    }

    /**
     * Получить массив расписания на преподавателя.
     */
    private function get_teacher($name = '', $arr = []) {
        if (!empty($name) && !empty($arr)) {
            $ret = [];
            $name = mb_strtoupper($name);
            foreach ($arr as $building)
                foreach($building['schedule'] as $schedule)
                        foreach($schedule['schedule'] as $lesson)
                            foreach ($lesson['teachers'] as $teacher)
                                if ($name == mb_strtoupper($teacher))
                                    $ret[$lesson['lesson']] = [
                                        'building' => $building['building'],
                                        'group' => $schedule['name'],
                                        'name' => $lesson['name'],
                                        'rooms' => $lesson['rooms'],
                                    ];
            ksort($ret, SORT_NUMERIC);
            return $ret;
        } else return false;
    }

    /**
     * Получить массив расписания на группу.
     */
    private function get_group($name = '', $arr = []) {
        if (!empty($name) && !empty($arr)) {
            $name = mb_strtoupper($name);
            foreach ($arr as $building)
                foreach ($building['schedule'] as $schedule)
                    if ($name == mb_strtoupper($schedule['name']))
                        return $schedule;
        }
    }
}
