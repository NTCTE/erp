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
    protected $target = 'publisher';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('formatted', 'Название'),
            TD::make('publisher.city_id', 'Город'),
            TD::make('actions', 'Действия')
                -> render(function (Publisher $publisher) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить')
                                -> icon('pencil')
                                -> modal('publisherModal')
                                -> modalTitle('Изменение информацию о издательстве')
                                -> method('create')
                                -> asyncParameters($publisher),
                        ]);
                }),
        ];
    }
}
