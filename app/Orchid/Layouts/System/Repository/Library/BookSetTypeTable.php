<?php

namespace App\Orchid\Layouts\System\Repository\Library;

use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BookSetTypeTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'bookSetTypes';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Название'),
            TD::make('actions', 'Действия')
                -> render(function ($bookSetType) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить')
                                -> icon('pencil')
                                -> modal('bookSetTypeModal')
                                -> modalTitle('Изменение информации о типе набора')
                                -> method('create')
                                -> asyncParameters($bookSetType),
                        ]);
                }),
        ];
    }
}
