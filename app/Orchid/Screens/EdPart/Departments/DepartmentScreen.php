<?php

namespace App\Orchid\Screens\EdPart\Departments;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Department;
use App\Orchid\Layouts\EdPart\Departments\GroupsTable;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class DepartmentScreen extends Screen
{
    public $department;
    public $groups;
    public $archived_groups;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Department $department): iterable
    {
        return [
            'department' => $department,
            'groups' => $department -> groups() -> paginate(),
            'archived_groups' => $department -> groups() -> where('archived', true) -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this -> department -> exists ? 'Редактирование отделения' : 'Создание отделения';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $ret = [
            Button::make('Сохранить')
                -> icon('save')
                -> method('save')
                -> canSee(!$this -> department -> exists && Auth::user() -> hasAccess('org.departments.write')),
            Button::make('Обновить')
                -> icon('refresh')
                -> method('save')
                -> confirm('Вы уверены, что хотите обновить данные отделения?')
                -> canSee($this -> department -> exists && Auth::user() -> hasAccess('org.departments.write')),
        ];

        if ($this -> department -> exists)
            $ret[] = Link::make('Создать группу')
                -> icon('plus')
                -> route('org.departments.group', [
                    'department' => $this -> department,
                ])
                -> canSee(Auth::user() -> hasAccess('org.departments.write'));
        
        return $ret;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('department.fullname')
                    -> title('Наименование отделения')
                    -> placeholder('Введите наименование отделения...')
                    -> required()
                    -> horizontal()
                    -> readonly(!Auth::user() -> hasAccess('org.departments.write')),
                Relation::make('department.manager_id')
                    -> title('Заведующий отделением')
                    -> fromModel(Person::class, 'lastname', 'id')
                    -> searchColumns('firstname', 'patronymic')
                    -> displayAppend('fullname')
                    -> placeholder('Выберите заведующего отделением...')
                    -> required()
                    -> horizontal()
                    -> disabled(!Auth::user() -> hasAccess('org.departments.write')),
            ]),
            new GroupsTable('Активные группы', 'groups', $this -> department -> exists),
            new GroupsTable('Архивные группы', 'archived_groups', $this -> department -> exists),
        ];
    }

    public function save(Department $department) {
        $department
            -> fill(request() -> input('department'))
            -> save();

        Toast::success('Отделение успешно сохранено!');

        return redirect()
            -> route('org.departments.entity', $department);
    }
}
