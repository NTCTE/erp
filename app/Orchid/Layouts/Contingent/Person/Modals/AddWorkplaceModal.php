<?php

namespace App\Orchid\Layouts\Contingent\Person\Modals;

use App\Models\System\Repository\Position;
use App\Models\System\Repository\Workplace;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class AddWorkplaceModal extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;
    protected $person_id;

    public function __construct($person_id) {
        $this -> person_id = $person_id;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Relation::make('workplace.fullname')
                -> title('Место работы')
                -> fromModel(Workplace::class, 'fullname', 'fullname')
                -> required(),
            Input::make('workplace.tel')
                -> title('Телефон')
                -> mask([
                    'mask' => '+7 (999) 999 99-99',
                ])
                -> help('Если вы укажете данные тут, то они перезапишут данные о работодателе в репозитории.'),
            Input::make('workplace.email')
                -> title('Адрес электронной почты')
                -> help('Если вы укажете данные тут, то они перезапишут данные о работодателе в репозитории.'),
            Relation::make('position.fullname')
                -> title('Должность')
                -> fromModel(Position::class, 'fullname', 'fullname')
                -> required(),
        ];
    }
}
