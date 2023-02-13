<?php

namespace App\Orchid\Layouts\Contingent\Person\Modals;

use App\Models\System\Repository\RelationType;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class AddRelative extends Rows
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
            Input::make('relative.lastname')
                -> class('form-control rebase')
                -> title('Фамилия')
                -> placeholder('Введите фамилию...')
                -> required(),
            Input::make('relative.firstname')
                -> class('form-control rebase')
                -> title('Имя')
                -> placeholder('Введите имя...')
                -> required(),
            Input::make('relative.patronymic')
                -> class('form-control rebase')
                -> title('Отчество')
                -> placeholder('Введите отчество...'),
            Select::make('rel_type')
                -> title('Тип родства')
                -> class('form-control rebase')
                -> fromModel(RelationType::class, 'fullname')
                -> empty('Выберите тип родства...'),
            Input::make('person_id')
                -> type('hidden')
                -> value($this -> person_id),
        ];
    }
}
