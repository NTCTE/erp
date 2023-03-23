<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Students\Tables;

use App\Models\Org\EdPart\Departments\StudentsAction;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ActionsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'student.actions';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('type', 'Тип действия'),
            TD::make('vanilla_name', 'Наименование группы при действии'),
            TD::make('additionals', 'Дополнительная информация'),
            TD::make('document', 'Административный документ')
                -> render(function (StudentsAction $action) {
                    return $action -> document ? $action -> document -> short : 'Нет документа';
                }),
            TD::make('created_at', 'Дата создания действия'),
            TD::make('updated_at', 'Дата изменения действия'),
        ];
    }
}
