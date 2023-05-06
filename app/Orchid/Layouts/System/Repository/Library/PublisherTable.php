<?php

namespace App\Orchid\Layouts\System\Repository\Library;

use App\Models\Org\Library\Additionals\Publisher;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PublisherTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'publishers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Название'),
            TD::make('city', 'Город')
                -> render(function (Publisher $publisher) {
                    return $publisher
                        ->city
                        ->fullname;
                }),
            TD::make('actions', 'Действия')
                -> render(function (Publisher $publisher) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить')
                                -> icon('pencil')
                                -> modal('publisherModal')
                                -> modalTitle('Изменение информацию об издательстве')
                                -> method('create')
                                -> asyncParameters($publisher),
                        ]);
                }),
        ];
    }
}
