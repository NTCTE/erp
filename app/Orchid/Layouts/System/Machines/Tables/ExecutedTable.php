<?php

namespace App\Orchid\Layouts\System\Machines\Tables;

use App\Models\System\Machines\Command;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ExecutedTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'machine.executed_commands';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('command', 'Команда')
                -> render(function (Command $command) {
                    return base64_decode($command -> command);
                }),
            TD::make('created_at', 'Дата выполнения'),
        ];
    }
}
