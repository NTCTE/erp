<?php

namespace App\Orchid\Layouts\Library;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LibraryTable extends Table
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
            TD::make('title', 'Название'),
            TD::make('booksetType', 'Тип набора'),
            TD::make('pertainingTitleInformation', 'Информация, относящаяся к заглавию'),
            TD::make('publishingYear', 'Год издания'),
            TD::make('ISBN', 'ISBN'),
            TD::make('pagesNumber', 'Количество страниц'),
            TD::make('language', 'Язык')
        ];
    }
}
