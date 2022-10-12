<?php

namespace App\Http\Controllers\EdPart\Schedule;

use App\Http\Controllers\Controller;
use App\Models\Legacy\Schedule\FullList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Shuchkin\SimpleXLSX;

class LegacyController extends Controller
{
    public function getSchedule($date, Request $request) {
        $schedule = FullList::where('date_at', $date) -> first();
        if (!empty($schedule))
            return [
                'date' => $schedule['date_at'],
                'countOfChanges' => $schedule
                    -> files
                    -> count(),
                'lastChange' => $schedule
                    -> files
                    -> max('updated_at'),
            ];
        else
            return response() -> json([
                'message' => 'Schedule on this date is not found'
            ], 404);
    }

    public function getList($date, $type) {
        $schedule = FullList::where('date_at', $date) -> first();
        if (!empty($schedule)) {
            $path = $schedule
                -> files()
                -> orderByDesc('updated_at')
                -> first()
                -> url;
            $path =  Storage::path("{$path['disk']}/{$path['path']}{$path['name']}.{$path['extension']}");
            $xlsx = SimpleXLSX::parse($path);
            $countOfBuildings = count($xlsx -> sheetNames());
            switch ($type) {
                case 'teachers':
                    
                break;
                case 'groups':

                break;
            }
        } else
            return response() -> json([
                'message' => 'Schedule on this date is not found'
            ], 404);
    }
}
