<?php

namespace App\Orchid\Layouts\Contingent\Person;

use App\Models\Org\Contingent\Document;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class DocumentsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'documents';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Наименование'),
            TD::make('actions', 'Действия')
                -> render(function (Document $document) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            Link::make('Редактировать')
                                -> route('org.contingent.person.document', [
                                    'id' => $document -> person_id,
                                    'type' => $document -> document_schema_id,
                                    'doc_id' => $document -> id,
                                ]),
                            Button::make('Удалить документ')
                                -> confirm('Вы уверены, что хотите удалить документ? Это действие необратимо.')
                                -> method('removeDoc',
                                    [
                                        'doc_id' => $document -> id,
                                    ]
                                ),
                        ]);
                })
                    -> canSee(Auth::user() -> hasAccess('org.contingent.write')),
        ];
    }
}
