<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Modals;

use App\Models\Org\EdPart\Departments\Group;
use App\Models\System\Repository\AdministrativeDocument;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class MoveStudentModal extends Rows
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
            Relation::make('student.group_id')
                -> required()
                -> title('Группа')
                -> placeholder('Выберите группу...')
                -> help('Поиск по курсу не происходит, вам нужно вводить имя группы без номера курса.')
                -> fromModel(Group::class, 'shortname')
                -> displayAppend('name')
                -> applyScope('archived', false),
            Relation::make('student.move_order_id')
                -> required()
                -> title('Приказ о переводе')
                -> allowEmpty()
                -> placeholder('Выберите Приказ о переводе...')
                -> fromModel(AdministrativeDocument::class, 'fullname', 'id')
                -> searchColumns('fullname', 'number', 'date_at')
                -> displayAppend('formatted')
                -> help('Вы можете выбрать только имеющийся Приказ о переводе, если вы хотите создать новый Приказ о переводе, то вам нужно сделать это через репозиторий административных документов. За студентом закрепится Приказ о зачислении группы, из которой он переводится. В случае, если у студента уже имеется запись о Приказе о зачислении, то она не изменится.'),
            TextArea::make('student.additionals')
                -> title('Дополнительные сведения')
                -> rows(5)
                -> placeholder('Введите дополнительные сведения...'),
            Input::make('student.id')
                -> type('hidden'),
            
        ];
    }
}
