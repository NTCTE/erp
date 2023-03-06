<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\AdministrativeDocument;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AdministrativeDocumentsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'docs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('formatted', 'Административный документ'),
            TD::make('actions', 'Действия')
                -> render(function (AdministrativeDocument $doc) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить')
                                -> icon('pencil')
                                -> modal('administrativeDocumentModal')
                                -> modalTitle('Изменение административного документа')
                                -> method('create')
                                -> asyncParameters($doc),
                        ]);
                }),
        ];
    }
}
