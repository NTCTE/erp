<?php

namespace App\Orchid\Screens\EdPart\Departments;

use App\Models\Org\EdPart\Departments\Department;
use App\Orchid\Layouts\EdPart\Departments\DepartmentsTable;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class MainScreen extends Screen
{
    public $departments;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'departments' => Department::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Отделения';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string {
        return 'Список отделений учебной части.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать отделение')
                -> icon('plus')
                -> route('org.departments.entity'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            DepartmentsTable::class,
        ];
    }
}
