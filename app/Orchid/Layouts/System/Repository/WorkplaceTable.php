<?php

namespace App\Orchid\Layouts\System\Repository;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class WorkplaceTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'workplace';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Наименование'),
            TD::make('tel', 'Телефон'),
            TD::make('email', 'Адрес электронной почты'),
        ];
    }
}
