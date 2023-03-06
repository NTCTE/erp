<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Rows;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Department;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
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
                -> title('Каноничное название')
                -> placeholder('Введите каноничное название группы')
                -> help('Вам нужно ввести каноничное название группы, без номера курса. Вместо номера курса введите #. Например, "#ИС-3".')
                -> horizontal()
                -> required(),
            Input::make('group.training_period')
                -> title('Срок обучения')
                -> placeholder('Введите срок обучения')
                -> help('Вам нужно ввести срок обучения в полных годах. Если срок обучения 3 года 10 месяцев, то введите 4.')
                -> horizontal()
                -> mask([
                    'alias' => 'integer',
                    'numericInput' => true,
                ])
                -> required(),
            Relation::make('group.department_id')
                -> title('Отделение')
                -> placeholder('Выберите отделение')
                -> fromModel(Department::class, 'fullname', 'id')
                -> value(request() 
                    -> route()
                    -> parameter('department')
                )
                -> horizontal()
                -> required(),
            Relation::make('group.curator_id')
                -> title('Куратор')
                -> placeholder('Выберите куратора')
                -> fromModel(Person::class, 'lastname', 'id')
                -> searchColumns('lastname', 'firstname', 'patronymic')
                -> displayAppend('fullname')
                -> horizontal()
                -> required(),
            CheckBox::make('group.archived')
                -> title('Архивная группа')
                -> help('Если группа является архивной, то она не будет отображаться в списке активных групп. Все архивные группы можно посмотреть в разделе "Архив групп" на экране соответствующего отделения. Архивация группы происходит автоматически, когда группа заканчивает свой срок обучения.')
                -> sendTrueOrFalse()
                -> horizontal(),
        ];
    }
}
