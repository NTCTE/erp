<?php

namespace App\Orchid\Screens\Library;

use App\Models\Org\Library\BookSet;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Picqer\Barcode\BarcodeGeneratorSVG;


class BookSetLegendScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(BookSet $bookSet): iterable
    {
        return [
            'bookset' => $bookSet,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Информация о комплекте';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('bookset', [
                Sight::make('id', 'Порядковый номер в БД'),
                Sight::make('title', 'Наименование'),
                Sight::make('cost' , 'Цена за экземпляр')
                    ->render(function (BookSet $bookSet) {
                        return $bookSet->cost . ' ₽';
                    }),
                Sight::make('book_set_type_id', 'Тип набора')
                    ->render(function (BookSet $bookSet) {
                        return $bookSet->bookSetType->fullname;
                    }),
                Sight::make('pertaining_title_information', 'Информация, относящаяся к заглавию')
                    ->render(function (BookSet $bookSet) {
                        return $bookSet->pertainingTitleInformation->fullname;
                    }),
                Sight::make('publishing_year', 'Год издания'),
                Sight::make('publication_information_id', 'Издательство')
                    ->render(function (BookSet $bookSet) {
                        return $bookSet->publisher->fullname;
                    }),
                Sight::make('author_id', 'Автор')
                    ->render(function (BookSet $bookSet) {
                        return $bookSet->authors?->getFullnameAtribute();
                    }),
                Sight::make('isbn', 'ISBN'),
                Sight::make('pages_number', 'Количество страниц'),
                Sight::make('annotation', 'Аннотация'),
                Sight::make('subject_headline_id', 'Предметный заголовок')
                    ->render(function (BookSet $bookSet) {
                        return $bookSet->subjectHeadline->fullname;
                    }),
                Sight::make('language_id', 'Язык')
                    ->render(function (BookSet $bookSet) {
                        return $bookSet->language->fullname;
                    }),
                Sight::make('basis_doc_id', 'Основание поступления комплекта')
                    ->render(function (BookSet $bookSet) {
                        return $bookSet->administrativeDocument->getFormattedAttribute();
                    }),
                Sight::make('barcode', 'Штриховой код')
                    ->render(function (BookSet $bookSet) {
                        if ($bookSet->barcode == null) {
                            return 'Штриховый код не предоставлен. Вы можете добавить его в окне редактирования комплекта.';
                        } else {
                            $generator = new BarcodeGeneratorSVG();
                            return $generator->getBarcode($bookSet->barcode, $generator::TYPE_CODE_128);
                        }
                    }),
                Sight::make('cover_id', 'Обложка')
                    ->render(function (BookSet $bookSet) {
                        $path = $bookSet->hero()->get()->first()?->url();
                        if ($path == null) {
                            return 'Обложка отсутствует';
                        } else {
                            $image = asset($path);
                            return "<img src='$image' width='300px' height='300px' alt='Обложка'>";
                        }}),
                Sight::make('digitized_id', 'Оцифрованная копия')
                    ->render(function (BookSet $bookSet) {
                        $path = $bookSet->digitized()->get()->first()?->url();
                        if ($path == null) {
                            return 'Оцифрованная копия книги не найдена';
                        } else {
                            return '<a href="' . asset($path) . '" target="_blank">Скачать</a>';
                        }
                    }),
            ])
        ];
    }
}
