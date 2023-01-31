<?php

namespace App\Orchid\Layouts\Contingent;

use App\Models\Org\Contingent\Person;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ContingentTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'persons';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'ФИО')
                -> render(function(Person $person) {
                    return Link::make($person -> fullname)
                        -> route('org.contingent.person', $person);
                }),
            TD::make('email', 'Адрес электронной почты'),
            TD::make('corp_email', 'Корпоративный адрес электронной почты'),
            TD::make('tel', 'Телефон'),
            TD::make('birthdate', 'Дата рождения'),
            TD::make('snils', 'СНИЛС'),
            TD::make('inn', 'ИНН'),
            TD::make('hin', 'ОМС'),
        ];
    }
}
