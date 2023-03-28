<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\DocumentSchema;
use App\Orchid\Fields\CenteredCheckbox;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class NewDocumentSchemaScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Добавить новую схему документа';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить')
                -> icon('save')
                -> confirm('Вы уверены, что хотите сохранить данную схему? Её нельзя удалить, только поставить в режим "для чтения".')
                -> method('saveSchema'),
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
            Layout::rows([
                Input::make('doc.fullname')
                    -> title('Наименование документа')
                    -> placeholder('Введите наименование...')
                    -> horizontal()
                    -> required(),
                Matrix::make('doc.schema')
                    -> title('Схема документа')
                    -> help('Внесите поля, которые нужно заполнять при добавлении документа к персоне.')
                    -> columns([
                        'Наименование' => 'title',
                        'Подсказка в поле' => 'placeholder',
                        'Текст-помощник' => 'help',
                        'Обязательно' => 'required',
                        'Тип поля' => 'type',
                    ])
                    -> fields([
                        'required' => CenteredCheckbox::make()
                            -> sendTrueOrFalse(),
                        'type' => Select::make()
                            -> options(DocumentSchema::$types),
                    ]),
            ]),
        ];
    }

    public function saveSchema(Request $request, DocumentSchema $document) {
        // $request -> validate([

        // ])

        // @ega22a: Опять-таки, нужна валидация данных!

        $document -> fill($request -> input('doc'))
            -> save();

        Toast::success('Схема документа успешно создана');

        return redirect()
            -> route('system.repository.documentSchemas');
    }
}
