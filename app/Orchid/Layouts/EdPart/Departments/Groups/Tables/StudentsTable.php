<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Tables;

use App\Models\Org\Contingent\Person;
use App\Models\System\Repository\AdministrativeDocument;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class StudentsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target;

    public function __construct(string $target, ?string $title = null)
    {
        $this -> target = $target;
        $this -> title($title);
        $this -> canSee(!empty(request() -> route() -> parameter('group')));
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'ФИО')
                -> render(function(Person $student) {
                    return Link::make($student -> fullname)
                        -> route('org.contingent.person', $student);
                })
                -> width('10%'),
            TD::make('birthdate', 'Дата рождения')
                -> render(function (Person $student) {
                    return !empty($student -> birthdate) ? "{$student -> birthdate} (" . ($student -> is_adult() ? 'совершеннолетний' : 'несовершеннолетний') . ")" : 'Не указана';
                }),
            TD::make('student', 'Бюджет')
                -> render(function (Person $student) {
                    return $student -> student -> budget ? 'Да' : 'Нет';
                }),
            TD::make('student', 'Приказ о зачислении')
                -> render(function (Person $student) {
                    return !empty($student -> student -> enrollment_order_id) ?
                        AdministrativeDocument::find($student -> student -> enrollment_order_id) -> formatted :
                        'Групповой';
                })
                -> width('20%'),
            TD::make('student', 'Академический отпуск')
                -> render(function (Person $student) {
                    $order = AdministrativeDocument::find($student -> student -> academic_leave['order_id']);
                    return "{$student -> student -> academic_leave['reason']}<br>{$order -> short}";
                })
                -> width('30%')
                -> canSee($this -> target == 'academic_leave'),
            TD::make('student', 'Дополнительные сведения')
                -> render(function(Person $student) {
                    return !empty($student -> student -> additionals) ?
                        $student -> student -> additionals :
                        'Не указаны';
                })
                -> width('30%'),
            TD::make('additionals', 'Действия')
                -> render(function(Person $person) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить дополнительные сведения')
                                -> icon('note')
                                -> modal('editAdditionalInfoModal')
                                -> method('editAdditionalInfo')
                                -> asyncParameters([
                                    'student' => $person -> student -> id,
                                ]),
                            ModalToggle::make('Перевести в другую группу')
                                -> icon('control-forward')
                                -> modal('moveStudentModal')
                                -> method('moveStudent')
                                -> asyncParameters([
                                    'id' => $person -> student -> id,
                                ])
                                -> confirm('Перед тем, как переводить студента в другую группу, нужно убедиться, что в системе имеется соответсвтующий Приказ. Если его нет, то необходимо сперва добавить Приказ о зачислении в систему.'),
                            ModalToggle::make('Перевести в академический отпуск')
                                -> icon('control-pause')
                                -> modal('moveStudentToAcademicLeaveModal')
                                -> method('moveStudentToAcademicLeave')
                                -> asyncParameters([
                                    'id' => $person -> student -> id,
                                ])
                                -> canSee(is_null($person -> student -> academic_leave)),
                            Link::make('Просмотреть историю движения')
                                -> icon('info')
                                -> route('org.departments.group.student.actions', [
                                    'department' => request() -> route() -> parameter('department'),
                                    'group' => request() -> route() -> parameter('group'),
                                    'student' => $person -> student,
                                ]),
                        ]);
                }),
        ];
    }
}
