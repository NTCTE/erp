<?php

namespace App\Orchid\Layouts\Library;

use App\Models\Org\Library\Additionals\BookSetType;
use App\Models\Org\Library\BookSet;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BookSetTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'booksets';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Название набора')
                ->render(function (BookSet $bookSet) {
                        return Link::make($bookSet->title)
                            ->route('library.bookset.edit', $bookSet);
                }),
            TD::make('actions', 'Действия')
                    -> render(function (BookSet $bookSet) {
                        return Link::make('')
                            -> icon('info')
                            -> route('library.bookset.info', $bookSet);
                    })
        ];
    }
}
