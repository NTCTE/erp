<?php

namespace App\Orchid\Layouts\System\Machines\Tables;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MachinesTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'machines';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('ip_address', 'IP-адрес'),
            TD::make('created_at', 'Дата регистрации'),
            TD::make('actions', 'Действия'),
        ];
    }
}
