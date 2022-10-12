<?php

namespace App\Orchid\Screens\EdPart\Schedule\Legacy;

use App\Models\Legacy\Schedule\Files;
use App\Models\Legacy\Schedule\FullList;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class Add extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Создать новую дату';
    }

    public function description(): ?string
    {
        return 'Страница добавления нового расписания. Если нужно, добавьте!';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить дату')
                -> icon('save')
                -> method('saveDate'),
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
            Layout::rows([
                DateTimer::make('schedule.date_at')
                    -> title('Дата расписания')
                    -> placeholder('Выберите дату...')
                    -> help('В последствии, вы не сможете удалить дату!')
                    -> required()
                    -> horizontal()
                    -> format('Y-m-d'),
                Upload::make('schedule.xlsx')
                    -> title('Расписание на дату')
                    -> groups('documents')
                    -> maxFiles(1)
                    -> acceptedFiles('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel')
                    -> horizontal()
                    -> required(),
            ]),
        ];
    }

    public function saveDate(Request $request, FullList $list, Files $files) {
        $request = $request -> get('schedule');

        $list -> fill([
            'date_at' => $request['date_at']
        ])
            -> save();
        $files -> fill([
            'schedule_id' => $list -> id,
            'attachment_id' => intval($request['xlsx'][0]),
        ])
            -> save();
        
        Alert::success('Расписание успешно создано на дату!');

        return redirect()
            -> route('schedule.legacy');
    }
}
