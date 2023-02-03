<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\DocumentSchema;
use App\Orchid\Layouts\System\Repository\DocumentsTable;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class DocumentSchemaScreen extends Screen
{
    public $documents;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(DocumentSchema $documents): iterable
    {
        return [
            'documents' => $documents::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Схемы документов';
    }

    public function description(): ?string
    {
        return 'Таблица со схемами документов, которые можно привязать к пользователю. Удалить схему нельзя, можно только перевести ее в режим "только для чтения"!';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новый документ')
                -> icon('doc')
                -> route('system.repository.documentSchemas.add'),
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
            DocumentsTable::class,
        ];
    }

    public function readonlyDoc(DocumentSchema $document) {
        $document -> fill([
            'readonly' => !boolval($document -> getAttributes()['readonly']),
        ])
            -> save();

        Toast::success("Статус \"{$document -> fullname}\" изменен");
    }
}
