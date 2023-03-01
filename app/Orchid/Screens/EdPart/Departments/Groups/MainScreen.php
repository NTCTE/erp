<?php

namespace App\Orchid\Screens\EdPart\Departments\Groups;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Department;
use App\Models\Org\EdPart\Departments\Group;
use App\Orchid\Layouts\EdPart\Departments\Groups\Rows\InformationRows;
use App\Orchid\Layouts\EdPart\Departments\Groups\Tables\StudentsTable;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Matrix;
use Orchid\Support\Facades\Toast;

class MainScreen extends Screen
{
    public $group;
    public $department;
    public $students;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $group = Group::find(request() -> route() -> parameter('group'));
        return [
            'department' => Department::find(request() -> route() -> parameter('department')),
            'group' => $group,
            'students' => !is_null($group) ? $group -> students : null,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return !is_null($this -> group) ? "Редактирование группы {$this -> group -> name()}" : 'Создание группы';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Создать')
                -> icon('save')
                -> method('save')
                -> canSee(is_null($this -> group)),
            Button::make('Обновить')
                -> icon('refresh')
                -> method('refresh')
                -> confirm('Вы уверены, что хотите обновить данные группы?')
                -> canSee(!is_null($this -> group)),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $layouts = [
            InformationRows::class,
        ];
        if (!is_null($this -> group))
            $layouts = array_merge($layouts, [
                Layout::modal('addStudentsModal', [
                    Layout::rows([
                        Matrix::make('students')
                            -> title('Студенты')
                            -> help('Остальные данные о студентах заполняются при добавлении персон.')
                            -> columns([
                                'Фамилия' => 'lastname',
                                'Имя' => 'firstname',
                                'Отчество' => 'patronymic',
                            ]),
                    ]),
                ])
                    -> withoutCloseButton()
                    -> applyButton('Добавить')
                    -> staticBackdrop()
                    -> size(Modal::SIZE_LG),
                Layout::rows([
                    ModalToggle::make('Добавить студентов')
                        -> modalTitle('Добавление студентов в группу')
                        -> modal('addStudentsModal')
                        -> method('addStudents')
                        -> icon('user-follow')
                        -> class('btn rebase'),
                ])
                    -> title('Контингент группы'),
                StudentsTable::class,
            ]);

        return $layouts;
    }

    public function save() {
        $group = request() -> input('group');
        $group['enrollment_date'] = Carbon::createFromFormat('d.m.Y', $group['enrollment_date']) -> format('Y-m-d');
        $group['uuid'] = Str::uuid();

        Toast::success('Группа успешно создана!');

        return redirect() -> route('org.departments.group', [
            'department' => $group['department_id'],
            'group' => Group::create($group) -> id,
        ]);
    }

    public function refresh() {
        $group = request() -> input('group');
        $group['enrollment_date'] = Carbon::createFromFormat('d.m.Y', $group['enrollment_date']) -> format('Y-m-d');

        Group::find(request() -> route() -> parameter('group'))
            -> update($group);

        Toast::success('Данные группы успешно обновлены!');

        return redirect() -> route('org.departments.group', [
            'department' => $group['department_id'],
            'group' => request() -> route() -> parameter('group'),
        ]);
    }
}
