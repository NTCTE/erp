<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Tables;

use App\Models\Org\Contingent\Person;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StudentsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'students';

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
            TD::make('tel', 'Номер телефона'),
            TD::make('additionals', 'Дополнительная информация'),
        ];
    }
}
