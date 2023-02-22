<?php

namespace App\Orchid\Layouts\Contingent\Person;

use App\Models\Org\Contingent\EducationalDocument;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EdDocsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'edDocs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $this -> title('Документы об образовании');

        return [
            TD::make('series', 'Серия'),
            TD::make('number', 'Номер'),
            TD::make('educational_doc_type_id', 'Тип документа')
                -> render(function (EducationalDocument $edDoc) {
                    return $edDoc -> educationalDocType -> fullname;
                })
                -> width('10%'),
            TD::make('educational_doc_issuer_id', 'Кем выдан')
                -> render(function (EducationalDocument $edDoc) {
                    return $edDoc -> educationalDocIssuer -> fullname;
                })
                -> width('40%'),
            TD::make('average_mark', 'Средний балл'),
            TD::make('date_of_issue', 'Дата выдачи'),
            TD::make('is_main', 'Основной')
                -> render(function (EducationalDocument $edDoc) {
                    return $edDoc -> is_main ? 'Да' : 'Нет';
                })
                -> width('10%'),
        ];
    }
}
