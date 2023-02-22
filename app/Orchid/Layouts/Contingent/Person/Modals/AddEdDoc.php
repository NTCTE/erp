<?php

namespace App\Orchid\Layouts\Contingent\Person\Modals;

use App\Models\System\Repository\EducationalDocIssuer;
use App\Models\System\Repository\EducationalDocType;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class AddEdDoc extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;
    protected $person;

    public function __construct(int $person)
    {
        $this -> person = $person;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('edDoc.series')
                -> title('Серия')
                -> placeholder('Введите серию документа...')
                -> required(),
            Input::make('edDoc.number')
                -> title('Номер')
                -> placeholder('Введите номер документа...')
                -> required(),
            Select::make('edDoc.educational_doc_type_id')
                -> title('Тип документа')
                -> fromModel(EducationalDocType::class, 'fullname', 'id')
                -> required(),
            Relation::make('edDoc.educational_doc_issuer_id')
                -> title('Кем выдан')
                -> allowAdd()
                -> fromModel(EducationalDocIssuer::class, 'fullname', 'id')
                -> required(),
            DateTimer::make('edDoc.date_of_issue')
                -> title('Дата выдачи')
                -> placeholder('Введите дату выдачи...')
                -> format('d.m.Y')
                -> required(),
            Input::make('edDoc.average_mark')
                -> title('Средний балл')
                -> placeholder('Введите средний балл...')
                -> required()
                -> mask([
                    'alias' => 'decimal',
                    'groupSeparator' => ' ',
                    'digits' => 3,
                    'prefix' => '',
                    'digitsOptional' => false,
                    'placeholder' => '0',
                    'numericInput' => true,
                ]),
            Input::make('edDoc.person_id')
                -> type('hidden')
                -> value($this -> person),
        ];
    }
}
