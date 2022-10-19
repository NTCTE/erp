<?php

namespace App\Orchid\Screens\EdPart\Schedule\Legacy;

use App\Models\Legacy\Schedule\FullList as ScheduleFullList;
use App\Orchid\Layouts\Legacy\Schedule\FullList as LegacyScheduleFullList;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class FullList extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'schedules' => ScheduleFullList::filters()
                -> defaultSort('id', 'desc')
                -> paginate(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Список расписаний (старый метод)';
    }

    public function description(): ?string
    {
        return 'Список расписаний, которые есть в системе. Это старый метод загрузки, так что используйте до того, пока вам не скажут использовать расписание по-новому!';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новое')
                -> icon('new-doc')
                -> route('schedule.legacy.add'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            LegacyScheduleFullList::class,
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'edPart.schedule.legacy.add'
        ];
    }
}
