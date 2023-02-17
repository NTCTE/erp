<?php

namespace App\Orchid\Layouts\Contingent\Person\Modals;

use App\Models\Org\Contingent\Person;
use App\Models\System\Repository\RelationType;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class AddRelativeExisting extends Rows
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
            Relation::make('relative.relative_id')
                -> title('Персона')
                -> fromModel(Person::class, 'lastname', 'id')
                -> searchColumns('firstname', 'patronymic')
                -> displayAppend('fullname')
                -> empty('Выберите персону...')
                -> required(),
            Select::make('relative.relation_type_id')
                -> title('Тип родства')
                -> class('form-control rebase')
                -> fromModel(RelationType::class, 'fullname')
                -> empty('Выберите тип родства...'),
            Input::make('relative.person_id')
                -> type('hidden')
                -> value($this -> person_id),
        ];
    }
}
