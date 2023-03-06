<?php

namespace App\Orchid\Screens\EdPart\Departments\Groups;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Department;
use App\Models\Org\EdPart\Departments\Group;
use App\Models\System\Relations\StudentsLink;
use App\Orchid\Layouts\EdPart\Departments\Groups\Rows\InformationRows;
use App\Orchid\Layouts\EdPart\Departments\Groups\Tables\StudentsTable;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group as FieldsGroup;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\TextArea;
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
            'students' => !is_null($group) ? $group
                -> students()
                -> where('is_academic_leave', false)
                -> paginate() : null,
            'academic_leave' => !is_null($group) ? $group
                -> students()
                -> where('is_academic_leave', true)
                -> paginate() : null,
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
            Layout::modal('studentEditModal', [
                Layout::rows([
                    TextArea::make('additionals')
                        -> title('Дополнительная информация')
                        -> placeholder('Введите дополнительную информацию...')
                        -> required()
                        -> rows(5),
                    Input::make('student_id')
                        -> type('hidden'),
                ]),
            ])
                -> withoutCloseButton()
                -> applyButton('Сохранить')
                -> async('asyncStudentEditModal'),
            InformationRows::class
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
                                'Дата рождения' => 'birthdate',
                                'Дополнительная информация' => 'additionals',
                            ])
                            -> fields([
                                'birthdate' => DateTimer::make()
                                    -> format('d.m.Y')
                                    -> required()
                                    -> placeholder('Введите...'),
                            ]),
                    ]),
                ])
                    -> withoutCloseButton()
                    -> applyButton('Добавить')
                    -> staticBackdrop()
                    -> size(Modal::SIZE_LG),
                Layout::rows([
                    FieldsGroup::make([
                        ModalToggle::make('Добавить студентов')
                            -> modalTitle('Добавление студентов в группу')
                            -> modal('addStudentsModal')
                            -> method('addStudents')
                            -> icon('user-follow')
                            -> class('btn rebase'),
                        Button::make('Регистрация студентов в системах')
                            -> icon('cloud-upload')
                            -> class('btn rebase'),
                    ])
                        -> autoWidth(),
                ])
                    -> title('Контингент группы'),
                new StudentsTable('students'),
                new StudentsTable('academic_leave', 'Академический отпуск'),
            ]);

        return $layouts;
    }

    public function asyncStudentEditModal(string $additionals, int $student_id) {
        return [
            'additionals' => $additionals,
            'student_id' => $student_id,
        ];
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

    public function addStudents() {
        $students = request() -> input('students');
        $group = Group::find(request() -> route() -> parameter('group'));
        foreach ($students as $student) {
            $person = Person::create([
                'lastname' => $student['lastname'],
                'firstname' => $student['firstname'],
                'patronymic' => $student['patronymic'],
                'uuid' => Str::uuid(),
            ]);
            StudentsLink::create([
                'person_id' => $person -> id,
                'group_id' => $group -> id,
            ]);
        }

        Toast::success('Студенты успешно добавлены в группу!');
    }

    public function studentRemove() {
        StudentsLink::where('person_id', request() -> input('person'))
            -> where('group_id', request() -> route() -> parameter('group'))
            -> delete();
        if (request() -> input('permanent'))
            Person::find(request() -> input('person'))
                -> delete();
    }

    public function studentEdit() {
        StudentsLink::where('person_id', request() -> input('student_id'))
            -> where('group_id', request() -> route() -> parameter('group'))
            -> first()
            -> update([
                'additionals' => request() -> input('additionals'),
            ]);

        Toast::success('Данные студента успешно обновлены!');
    }

    public function studentAcademicLeave() {
        $student = StudentsLink::where('person_id', request() -> input('person'))
            -> where('group_id', request() -> route() -> parameter('group'))
            -> first();
        $student -> fill([
            'is_academic_leave' => request() -> input('status'),
            'steps_counter' => $student -> steps_counter + 1,
        ])
            -> save();

        Toast::success('Данные студента успешно обновлены!');
    }
}
