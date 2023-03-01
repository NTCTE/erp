<?php

namespace App\Orchid\Layouts\EdPart\Departments;

use App\Models\Org\EdPart\Departments\Department;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class DepartmentsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'departments';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Наименование')
                -> render(function (Department $department) {
                    return Link::make($department -> fullname)
                        -> route('org.departments.entity', $department);
                }),
            TD::make('manager', 'Заведующий')
                -> render(function (Department $department) {
                    return Link::make($department -> manager -> fullname)
                        -> route('org.contingent.person', $department -> manager);
                }),
        ];
    }
}
