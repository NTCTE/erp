<?php

namespace App\Orchid\Layouts\System\Machines\Tables;

use App\Models\System\Machines\Command;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CommandsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'commands';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'Номер команды')
                -> width('25%'),
            TD::make('command', 'Команда')
                -> render(function(Command $command) {
                    return base64_decode($command -> command);
                })
                -> width('40%'),
            TD::make('successes', 'Успешно выполнено')
                -> width('10%'),
            TD::make('created_at', 'Дата создания')
                -> width('15%'),
        ];
    }
}
