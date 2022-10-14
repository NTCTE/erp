<?php

namespace App\Orchid\Screens\EdPart\Schedule\Legacy;

use App\Models\Legacy\Schedule\Files;
use App\Models\Legacy\Schedule\FullList;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class Edit extends Screen
{
    public $scheduleItem;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(FullList $list): iterable
    {
        return [
            'scheduleItem' => $list,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return "Внести изменения в расписание на {$this -> scheduleItem -> date_at} число";
    }

    public function description(): ?string
    {
        return 'Вы можете добавить измененное расписание, но не можете удалить уже внесенные!';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Внеси изменение')
                -> icon('pencil')
                -> method('addChange'),
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
                Upload::make('xlsx')
                    -> title('Измененное расписание')
                    -> groups('documents')
                    -> maxFiles(1)
                    -> acceptedFiles('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel')
                    -> horizontal()
                    -> required(),
            ]),
        ];
    }

    public function permission(): ?iterable
    {
        return [
            'edPart.schedule.legacy.add'
        ];
    }

    public function addChange(FullList $fullList, Request $request) {
        $file = new Files();
        $file -> fill([
            'schedule_id' => $fullList -> id,
            'attachment_id' => $request -> get('xlsx')[0],
        ])
            -> save();

        Alert::success('Изменение в расписании успешно сохранено!');

        return redirect()
            -> route('schedule.legacy');
    }
}
