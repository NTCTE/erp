<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\Specialty;
use App\Orchid\Layouts\System\Repository\SpecialtiesTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SpecialtiesScreen extends Screen
{
    public $specialties;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Specialty $specialty): iterable
    {
        return [
            'specialties' => $specialty -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Список специальностей';
    }

    public function description(): ?string
    {
        return 'Список специальностей, используемых в системе. Этот список редактируется только тут.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить специальность')
                -> icon('plus')
                -> modal('addSpecialtyModal')
                -> method('create')
                -> modalTitle('Добавить специальность')
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
            Layout::modal('addSpecialtyModal', [
                Layout::rows([
                    Input::make('specialty.code')
                        -> title('Код специальности')
                        -> placeholder('Введите код специальности...')
                        -> required(),
                    Input::make('specialty.fullname')
                        -> title('Полное название специальности')
                        -> placeholder('Введите полное название специальности...')
                        -> required(),
                    Input::make('specialty.id')
                        -> type('hidden'),
                ])
            ])
                -> withoutCloseButton()
                -> applyButton('Сохранить')
                -> async('asyncModalSave'),
            SpecialtiesTable::class,
        ];
    }

    public function asyncModalSave(int $id = null, string $code = null, string $fullname = null): array {
        return [
            'specialty' => [
                'id' => $id,
                'code' => $code,
                'fullname' => $fullname,
            ],
        ];
    }

    public function create() {
        $get = request() -> get('specialty');
        if ($specialty = Specialty::find($get['id']))
            $specialty -> update($get);
        else
            Specialty::create($get);

        Toast::info('Специальность успешно сохранена');
    }

    public function delete() {
        Specialty::find(request() -> get('id')) -> delete();
        
        Toast::info('Специальность успешно удалена');
    }
}
