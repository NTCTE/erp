<?php

namespace App\Orchid\Layouts\Contingent\Person\Personal;

use App\Models\Org\Contingent\Person;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class Personal extends Rows
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
            Input::make('person.lastname')
                -> title('Фамилия')
                -> placeholder('Введите фамилию...')
                -> required()
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
            Input::make('person.firstname')
                -> title('Имя')
                -> placeholder('Введите имя...')
                -> required()
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
            Input::make('person.patronymic')
                -> title('Отчество')
                -> placeholder('Введите отчество...')
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
            DateTimer::make('person.birthdate')
                -> title('Дата рождения')
                -> placeholder('Введите дату рождения...')
                -> format('d.m.Y')
                -> canSee(Auth::user() -> hasAccess('org.contingent.write')),
            Input::make('person.birthdate')
                -> title('Дата рождения')
                -> placeholder('Введите дату рождения...')
                -> canSee(!Auth::user() -> hasAccess('org.contingent.write')),
            Select::make('person.sex')
                -> title('Пол')
                -> options(Person::$sexs)
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
        ];
    }
}
