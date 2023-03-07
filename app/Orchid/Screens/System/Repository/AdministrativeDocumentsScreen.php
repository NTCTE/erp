<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\AdministrativeDocument;
use App\Orchid\Layouts\System\Repository\AdministrativeDocumentsTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AdministrativeDocumentsScreen extends Screen
{
    public $docs;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(AdministrativeDocument $docs): iterable
    {
        return [
            'docs' => $docs -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Административные документы';
    }

    public function description():? string {
        return 'Список административных документов, используемых в системе. Можно добавить на этом экране, также добавляются в другим местах системы.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить документ')
                -> modal('administrativeDocumentModal')
                -> method('create')
                -> icon('plus')
                -> modalTitle('Создание административного документа'),
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
            Layout::modal('administrativeDocumentModal', [
                Layout::rows([
                    Select::make('doc.type')
                    -> title('Вид административного документа')
                    -> empty('Выберите вид документа...')
                    -> options(AdministrativeDocument::$types)
                    -> required(),
                    Input::make('doc.number')
                    -> title('Номер административного документа')
                    -> placeholder('Введите номер документа...')
                    -> required(),
                    DateTimer::make('doc.date_at')
                    -> title('Дата административного документа')
                    -> placeholder('Введите дату документа...')
                    -> format('d.m.Y')
                    -> required(),
                    TextArea::make('doc.fullname')
                    -> title('Полное наименование административного документа')
                    -> placeholder('Введите полное наименование документа...')
                    -> rows(5)
                    -> required(),
                    Input::make('doc.id')
                        -> type('hidden'),
                ]),
            ])
                -> withoutCloseButton()
                -> applyButton('Сохранить')
                -> staticBackdrop()
                -> async('asyncGetAdministrativeDocument'),
            AdministrativeDocumentsTable::class,
        ];
    }

    public function asyncGetAdministrativeDocument(array $fields = null): array {
        return is_null($fields) ? [] : [
            'doc' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('doc');
        if ($doc = AdministrativeDocument::find($get['id']))
            $doc -> update($get);
        else
            AdministrativeDocument::create($get);
        
        Toast::info('Данные сохранены');
    }
}