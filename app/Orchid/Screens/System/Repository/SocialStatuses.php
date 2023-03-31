<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\SocialStatus;
use App\Orchid\Layouts\System\Repository\SocialStatusesTable;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SocialStatuses extends Screen
{
    public $statuses;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(SocialStatus $statuses): iterable
    {
        return [
            'statuses' => $statuses::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Социальные статусы персон';
    }

    public function description():? string {
        return 'Список социальных статусов, используемых в системе. Добавляются и редактируются только на этом экране.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить')
                -> modal('socialStatusModal')
                -> method('create')
                -> icon('plus')
                -> modalTitle('Создание социального статуса'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::modal('socialStatusModal', [
                Layout::rows([
                    Input::make('status.fullname')
                        -> title('Название')
                        -> placeholder('Введите название социального статуса')
                        -> required(),
                    Input::make('status.id')
                        -> type('hidden'),
                ]),
            ])
                -> withoutCloseButton()
                -> applyButton('Создать')
                -> async('asyncModalSave'),
            SocialStatusesTable::class,
        ];
    }

    public function asyncModalSave(int $id = null, string $fullname = null): array {
        return [
            'status' => [
                'id' => $id,
                'fullname' => $fullname,
            ]
        ];
    }

    public function create(Request $request) {

        $request->validate([
            'status.fullname' => 'required|string|max:200',
        ], [
            'status.fullname.required' => 'Поле "Название" является обязательным.',
            'status.fullname.string' => 'Поле "Название" должно быть строкой.',
            'status.fullname.max' => 'Длина поля "Название" не должна превышать 255 символов.',

        ]);
        $get = request() -> get('status');
        if ($status = SocialStatus::find($get['id']))
            $status -> update($get);
        else
            SocialStatus::create($get);

        Toast::info('Социальный статус успешно создан');
    }

    public function delete() {
        SocialStatus::find(request() -> get('id')) -> delete();

        Toast::info('Социальный статус успешно удален');
    }
}
