<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Modals;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Layouts\Rows;

class AddStudentsModal extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Matrix::make('newStudents')
                -> help('На данном этапе, вы регистрируете новых персон в систему, которые сразу привязываются к группе. После регистрации, вы можете редактировать данные персон, а также добавлять документы, которые относятся к ним. Если вы регистрируете студентов, которые добавились в пополнение контингента, то вам нужно будет дополнительно изменить данные для них. ВНИМАНИЕ! Если вы переводите одного студента в другую группу, то это делается не тут, а в соответствующей группе.')
                -> columns([
                    'Фамилия' => 'lastname',
                    'Имя' => 'firstname',
                    'Отчество' => 'patronymic',
                ])
        ];
    }
}
