<?php

namespace App\Orchid\Layouts\Contingent\Person\Modals;

use App\Models\System\Repository\PassportIssuer;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class AddPassportModal extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;
    protected $person;

    public function __construct(int $person) {
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
            Input::make('passport.series')
                -> title('Серия паспорта')
                -> placeholder('Введите серию паспорта...')
                -> help('Если серии у паспорта нет, ничего не вводите.'),
            Input::make('passport.number')
                -> title('Номер паспорта')
                -> placeholder('Введите номер паспорта...')
                -> required(),
            Relation::make('passport.passport_issuer_id')
                -> title('Кем выдан')
                -> placeholder('Выберите орган, выдавший паспорт...')
                -> fromModel(PassportIssuer::class, 'fullname', 'id')
                -> searchColumns('fullname', 'code')
                -> displayAppend('formatted')
                -> required(),
            DateTimer::make('passport.date_of_issue')
                -> title('Дата выдачи')
                -> placeholder('Выберите дату выдачи паспорта...')
                -> format('d.m.Y')
                -> required(),
            TextArea::make('passport.birthplace')
                -> title('Место рождения')
                -> placeholder('Введите место рождения...'),
            Input::make('passport.person_id')
                -> value($this -> person)
                -> type('hidden'),
        ];
    }
}
