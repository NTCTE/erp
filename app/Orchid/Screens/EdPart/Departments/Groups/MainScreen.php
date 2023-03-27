<?php

namespace App\Orchid\Screens\EdPart\Departments\Groups;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Department;
use App\Models\Org\EdPart\Departments\Group;
use App\Models\System\Relations\AdministativeDocumentsLinks;
use App\Models\System\Relations\StudentsLink;
use App\Models\System\Repository\AdministrativeDocument;
use App\Notifications\EdPart\Departments\Groups\NotAddedStudentsNotification;
use App\Orchid\Layouts\EdPart\Departments\Groups\Rows\OrderListener;
use App\Orchid\Layouts\EdPart\Departments\Groups\Rows\InformationRows;
use App\Orchid\Layouts\EdPart\Departments\Groups\Tables\StudentsTable;
use App\Orchid\Layouts\EdPart\Departments\Groups\Modals\AddStudentsModal;
use App\Orchid\Layouts\EdPart\Departments\Groups\Modals\EditAdditionalInfoModal;
use App\Orchid\Layouts\EdPart\Departments\Groups\Modals\MoveToAcademicLeaveModal;
use App\Traits\System\Listeners\Order;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group as FieldsGroup;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class MainScreen extends Screen
{
    use Order;
    
    public $group;
    public $department;
    public $students;
    public $order;
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
            'orders' => !is_null($group) ? $group -> orders() -> paginate() : null,
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
                -> canSee(is_null($this -> group) && Auth::user() -> hasAccess('org.departments.write')),
            Button::make('Обновить')
                -> icon('refresh')
                -> method('refresh')
                -> confirm('Вы уверены, что хотите обновить данные группы?')
                -> canSee(!is_null($this -> group) && Auth::user() -> hasAccess('org.departments.write')),
        ];
    }

    public function permission(): iterable {
        return [
            'org.departments.*',
        ];
    }

    public function asyncEditAdditionalInfo(): array {
        return [
            'student' => [
                'additionals' => request() -> input('additionals'),
                'enrollment_order_id' => request() -> input('enrollment_order_id'),
                'budget' => request() -> input('budget'),
                'id' => request() -> input('id'),
            ],
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
            Layout::modal('addStudentsModal', AddStudentsModal::class)
                -> title('Добавление студентов')
                -> withoutCloseButton()
                -> applyButton('Добавить')
                -> staticBackdrop()
                -> size(Modal::SIZE_LG)
                -> canSee(!empty(request() -> route() -> parameter('group'))),
            Layout::modal('editAdditionalInfoModal', EditAdditionalInfoModal::class)
                -> title('Редактирование дополнительной информации')
                -> withoutCloseButton()
                -> applyButton('Сохранить')
                -> staticBackdrop()
                -> async('asyncEditAdditionalInfo'),
            Layout::modal('moveStudentToAcademicLeaveModal', MoveToAcademicLeaveModal::class)
                -> title('Перевод студента в академический отпуск')
                -> withoutCloseButton()
                -> applyButton('Перевести')
                -> staticBackdrop()
                -> async('asyncMoveStudent'),
            InformationRows::class,
            OrderListener::class,
            Layout::rows([
                FieldsGroup::make([
                    ModalToggle::make('Добавить студентов')
                        -> modal('addStudentsModal')
                        -> method('addStudents')
                        -> icon('plus')
                        -> class('btn rebase'),
                ])
                    -> autoWidth(),
            ])
                -> title('Активные студенты')
                -> canSee(!empty(request() -> route() -> parameter('group')) && Auth::user() -> hasAccess('org.departments.write')),
            new StudentsTable('students'),
            new StudentsTable('academic_leave', 'В академическом отпуске'),
        ];
    }

    public function save() {
        $group = Group::create(request() -> input('group'));

        foreach (request() -> orders as $order) {
            if (request() -> input('async.existing_order_cb')) {
                AdministativeDocumentsLinks::create([
                    'administrative_document_id' => $order['id'],
                    'signed_id' => $group -> id,
                    'signed_type' => Group::class,
                    'description' => $order['description'],
                ]);
            } else {
                AdministativeDocumentsLinks::create([
                    'administrative_document_id' => AdministrativeDocument::create(array_merge($order, ['type' => 1])) -> id,
                    'signed_id' => $group -> id,
                    'signed_type' => Group::class,
                    'description' => $order['description'],
                ]);
            }
        }

        Toast::success('Группа успешно создана');

        return redirect() -> route('org.departments.group', [
            'department' => request() -> route() -> parameter('department'),
            'group' => $group -> id,
        ]);
    }

    public function addStudents() {
        if (!empty(request() -> newStudents)) {
            $notAdded = [];
            foreach (request() -> newStudents as $key => $student) {
                if (!empty($student['lastname']) && !empty($student['firstname'])) {
                    (new StudentsLink)
                        -> fill([
                            'person_id' => Person::create($student) -> id,
                            'group_id' => request() -> route() -> parameter('group'),
                            'enrollment_order_id' => $student['order'],
                        ])
                        -> setActions(1)
                        -> save();
                } else $notAdded[] = [
                    'key' => $key,
                    'student' => $student,
                ];
            }
            if (!empty($notAdded)) {
                $message = '';
                foreach ($notAdded as $student) {
                    $message .= "Студент с ключом {$student['key']} не был добавлен, так как не были указаны фамилия и имя. Полученные данные о студенте:<br><p style='margin-left: 10px;'>Фамилия: \"{$student['student']['lastname']}\"; Имя: \"{$student['student']['firstname']}\"; Отчество: \"{$student['student']['patronymic']}\"</p>";
                }
                Auth::user() -> notify(new NotAddedStudentsNotification($message));
                Toast::info('Не все студенты были добавлены, так как не были указаны фамилия и имя. Дополнительную информацию вы можете узнать в уведомлениях.');
            } else Toast::success('Студенты успешно добавлены');
        } else Toast::warning('Не было передано ни одного студента!');
    }

    public function editAdditionalInfo() {
        $student = request() -> input('student');

        StudentsLink::find($student['id'])
            -> setActions()
            -> update($student);

        Toast::info('Дополнительная информация успешно обновлена');
    }
}
