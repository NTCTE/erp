<?php

namespace App\Orchid\Layouts\Legacy\Schedule;

use App\Models\Legacy\Schedule\Files;
use App\Models\Legacy\Schedule\FullList as ScheduleFullList;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FullList extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'schedules';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('date_at', 'Дата расписания')
                -> sort()
                -> render(function(ScheduleFullList $list) {
                    return Link::make($list -> date_at)
                        -> route('schedule.legacy.item', $list);
                }),
            TD::make('updated_at', 'Дата последнего изменения')
                -> sort()
                -> render(function(ScheduleFullList $list) {
                    $files = new Files();
                    return Date(
                        'd.m.Y H:i:s',
                        strtotime($files::where('schedule_id', $list -> id) -> max('updated_at'))
                    );
                }),
            TD::make('countOfChanges', 'Количество изменений в расписании')
                -> render(function(ScheduleFullList $list) {
                    $files = new Files();
                    return $files::where('schedule_id', $list -> id) -> count();
                }),
        ];
    }
}
