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
        $request -> validate([
            'doc.fullname' => 'required|string|max:200',
            'doc.schema.*.title' => 'required|string|max:255',
            'doc.schema.*.placeholder' => 'nullable|string|max:255',
            'doc.schema.*.help' => 'nullable|string|max:255',
            'doc.schema.*.required' => 'required|boolean',
            'doc.schema.*.type' => 'required|in:text,number,date,email,tel,datetime-local,month,week,time',
        ], [
            'doc.fullname.required' => 'Поле "Наименование документа" является обязательным.',
            'doc.fullname.string' => 'Поле "Наименование документа" должно быть строкой.',
            'doc.fullname.max' => 'Поле "Наименование документа" не должно превышать 255 символов.',
            'doc.schema.*.title.required' => 'Заголовок поля является обязательным.',
            'doc.schema.*.title.string' => 'Заголовок поля должен быть строкой.',
            'doc.schema.*.title.max' => 'Заголовок поля не должен превышать 255 символов.',
            'doc.schema.*.placeholder.string' => 'Подсказка в поле должна быть строкой.',
            'doc.schema.*.placeholder.max' => 'Подсказка в поле не должна превышать 255 символов.',
            'doc.schema.*.required.required' => 'Поле "Обязательно" является обязательным.',
            'doc.schema.*.required.boolean' => 'Поле "Обязательно" должно быть булевым значением.',
            'doc.schema.*.type.required' => 'Поле "Тип поля" является обязательным.',
            'doc.schema.*.type.in' => 'Поле "Тип поля" должно иметь одно из значений: текстовое поле,числовое поле,адрес электронной почты,номер телефона,дата,дата и время,месяц,неделя,время',

        ]);


        $document -> fill($request -> input('doc'))
            -> save();

        Toast::success('Схема документа успешно создана');

        return redirect()
            -> route('system.repository.documentSchemas');
    }
}
