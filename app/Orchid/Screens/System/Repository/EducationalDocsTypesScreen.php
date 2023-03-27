<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\EducationalDocType;
use App\Orchid\Layouts\System\Repository\EducationalDocsTypesTable;
use Orchid\Screen\Actions\ModalToggle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EducationalDocsTypesScreen extends Screen
{
    public $types;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(EducationalDocType $types): iterable
    {
        return [
            'types' => $types -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Типы документов об образовании';
    }

    public function description(): ?string
    {
        return 'Репозиторий типов документов об образовании. Данные заполнены в системе, если нужно дополнить, то вы можете это сделать.';
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
                -> icon('plus')
                -> modal('modalCreate')
                -> method('create'),
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
            Layout::modal('modalCreate', [
                Layout::rows([
                    Input::make('fullname')
                        -> title('Наименование')
                        -> placeholder('Введите наименование')
                        -> required(),
                ]),
            ])
                -> withoutCloseButton()
                -> title('Добавить тип документа об образовании')
                -> applyButton('Добавить'),
            EducationalDocsTypesTable::class,
        ];
    }

    public function changeReadonly() {
        $type = EducationalDocType::find(request('id'));
        $type -> readonly = !$type -> readonly;
        $type -> save();

        Toast::success('Тип документа об образовании успешно изменен.');
    }

    public function create(Request $request)
    {
       $request ->validate([
            'fullname' => ['required', 'string', 'max:255'],
        ], [
            'fullname.required' => 'Поле обязательно для заполнения',
            'fullname.string' => 'Значение должно быть строкой',
            'fullname.max' => 'Значение не должно превышать 255 символов',
        ]);

        $type = EducationalDocType::create([
            'fullname' => $request->input('fullname'),
        ]);

        Toast::success('Тип документа об образовании успешно добавлен.');
    }
}
