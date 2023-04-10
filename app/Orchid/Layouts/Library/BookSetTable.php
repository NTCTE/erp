<?php

namespace App\Orchid\Layouts\Library;

use App\Models\Org\Library\Additionals\BookSetType;
use App\Models\Org\Library\BookSet;
use Orchid\Screen\Actions\Link;
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
            TD::make('cost', 'Стоимость набора'),
            TD::make('book_set_type_id', 'Тип набора')
                ->render(function (BookSet $bookSet) {
                    return $bookSet->bookSetType->fullname;
                }),
            TD::make('pertaining_title_information_id', 'Сведения о наборе')
                ->render(function (BookSet $bookSet) {
                    return $bookSet->pertainingTitleInformation->fullname;
                }),
            TD::make('publishing_year', 'Год издания'),
            TD::make('publisher_id', 'Издатель')
                ->render(function (BookSet $bookSet) {
                    return $bookSet->publisher->fullname;
                }),
            TD::make('isbn', 'ISBN'),
            TD::make('pages_number', 'Количество страниц'),
            TD::make('annotation', 'Аннотация'),
            TD::make('subject_headline_id', 'Тематический заголовок')
                ->render(function (BookSet $bookSet) {
                    return $bookSet->subjectHeadline->fullname;
                }),
            TD::make('language_id', 'Язык'),
            TD::make('basis_doc_id', 'Основание')
                ->render(function (BookSet $bookSet) {
                    return $bookSet->administrativeDocument;
                }),
            TD::make('barcode', 'Штрих-код'),
        ];
    }
}
