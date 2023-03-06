<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Tables;

use App\Models\Org\Contingent\Person;
use App\Models\System\Relations\StudentsLink;
use Orchid\Screen\Actions\Button;
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
                -> render(function(Person $person) {
                    return Link::make($person -> fullname)
                        -> route('org.contingent.person', $person);
                }),
            TD::make('email', 'Адрес электронной почты'),
            TD::make('tel', 'Номер телефона'),
            TD::make('birthdate', 'Дата рождения'),
            TD::make('additionals', 'Дополнительная информация')
                -> render(function(Person $person) {
                    return StudentsLink::firstWhere('person_id', $person -> id)
                        -> additionals;
                })
                -> width('20%'),
            TD::make('actions', 'Действия')
                -> render(function(Person $person) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Редактировать студента')
                                -> icon('pencil')
                                -> modal('studentEditModal')
                                -> modalTitle('Редактирование студента')
                                -> method('studentEdit')
                                -> asyncParameters([
                                    StudentsLink::where('person_id', $person -> id)
                                        -> where('group_id', request() -> route() -> parameter('group'))
                                        -> first() -> additionals,
                                    $person -> id,
                                ]),
                            Button::make('Отчислить')
                                -> icon('user-unfollow')
                                -> method('studentRemove', [
                                    'person' => $person -> id, 
                                    'permanent' => false,
                                ])
                                -> confirm('Вы собираетесь отчислить студента из группы без удаления персоны. Продолжить?'),
                            Button::make('Отчислить и удалить персону')
                                -> icon('user-unfollow')
                                -> method('studentRemove')
                                -> method('studentRemove', [
                                    'person' => $person -> id, 
                                    'permanent' => true,
                                ])
                                -> confirm('ВНИМАНИЕ! Вы собираетесь отчислить студенты с удалением персоны из системы. Это означает, что никаких записей о персоне не останется в системе. Продолжить?'),
                            ModalToggle::make('Перевести в другую группу')
                                -> icon('anchor')
                                -> modal('studentTransferModal')
                                -> modalTitle('Перевод студента в другую группу')
                                -> method('studentTransfer', [
                                    'person' => $person -> id,
                                ])
                                -> confirm('Вы собираетесь перевести студента в другую группу. Продолжить?'),
                            Button::make('Перевести студента в академический отпуск')
                                -> icon('control-pause')
                                -> method('studentAcademicLeave', [
                                    'person' => $person -> id,
                                    'status' => true,
                                ])
                                -> confirm('Вы собираетесь перевести студента в академический отпуск. Продолжить?'),
                        ]);
                }),
        ];
    }
}
