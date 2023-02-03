<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\DocumentSchema;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
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
            TD::make('fullname', 'Название документа'),
            TD::make('readonly', 'В режиме чтения'),
            TD::make('actions', 'Действия')
                -> render(function(DocumentSchema $document) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            Button::make('Изменить режим использования')
                                -> icon(boolval($document -> readonly) ? 'lock-open' : 'lock')
                                -> confirm(boolval($document -> readonly) ?
                                'Если вы сейчас это сделаете, то нельзя будет добавить этот документ к персоне. Продолжить?' :
                                'В данном случае, вы даете возможность использовать этот документ. Продолжить?')
                                -> method('readonlyDoc', [$document -> id]),  
                        ]);
                }),
        ];
    }
}
