<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Rows;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Department;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class InformationRows extends Rows
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
            Input::make('group.shortname')
                -> title('Краткое имя группы')
                -> placeholder('Введите краткое имя группы...')
                -> help('Например, ИС-3, ИС-6, ИС-9. Номер курса не указывается, он будет добавлен автоматически. Вставьте симовол "#" там, где нужно вставить номер курса.')
                -> required()
                -> horizontal(),
            DateTimer::make('group.enrollment_date')
                -> title('Дата зачисления')
                -> placeholder('Введите дату зачисления...')
                -> format('d.m.Y')
                -> required()
                -> horizontal(),
            Input::make('group.training_period')
                -> title('Срок обучения')
                -> placeholder('Введите срок обучения...')
                -> help('Вводите полное число лет. Если срок обучения составляет 3 года и 10 месяцев, то введите 4.')
                -> mask([
                    'alias' => 'integer',
                    'numericInput' => true,
                ])
                -> required()
                -> horizontal(),
            Relation::make('group.department_id')
                -> title('Отделение')
                -> placeholder('Выберите отделение...')
                -> fromModel(Department::class, 'fullname', 'id')
                -> required()
                -> horizontal(),
            Relation::make('group.curator_id')
                -> title('Куратор')
                -> placeholder('Выберите куратора...')
                -> fromModel(Person::class, 'lastname', 'id')
                -> searchColumns('firstname', 'patronymic')
                -> displayAppend('fullname')
                -> required()
                -> horizontal(),
            CheckBox::make('group.archived')
                -> title('Архивная группа')
                -> help('Если группа является архивной, то она не будет отображаться в списке активных групп. Все архивные группы можно посмотреть в разделе "Архив групп" на экране соответствующего отделения. Архивация группы происходит автоматически, когда группа заканчивает свой срок обучения.')
                -> sendTrueOrFalse()
                -> horizontal(),
        ];
    }
}
