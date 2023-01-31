<?php

namespace App\Orchid\Layouts\System\Repository;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PositionTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'position';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Наименование'),
        ];
    }
}
