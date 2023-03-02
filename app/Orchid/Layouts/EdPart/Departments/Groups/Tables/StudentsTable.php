<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Tables;

use App\Models\Org\Contingent\Person;
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
    protected $target = 'students';

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
            TD::make('additionals', 'Дополнительная информация'),
            TD::make('actions', 'Действия')
                -> render(function(Person $person) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
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
                                -> modal('studentTransfer')
                                -> modalTitle('Перевод студента в другую группу')
                                -> method('studentTransfer', [
                                    'person' => $person -> id,
                                ])
                                -> confirm('Вы собираетесь перевести студента в другую группу. Продолжить?'),
                            ModalToggle::make('Перевести студента в академический отпуск')
                                -> icon('control-pause')
                                -> modal('studentAcademicLeave')
                                -> modalTitle('Перевод студента в академический отпуск')
                                -> method('studentAcademicLeave', [
                                    'person' => $person -> id,
                                ])
                                -> confirm('Вы собираетесь перевести студента в академический отпуск. Продолжить?'),
                        ]);
                }),
        ];
    }
}
