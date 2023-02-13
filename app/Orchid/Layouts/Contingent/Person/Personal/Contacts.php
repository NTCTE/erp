<?php

namespace App\Orchid\Layouts\Contingent\Person\Personal;

use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class Contacts extends Rows
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
            Input::make('person.email')
                -> title('Личный адрес электронной почты')
                -> placeholder('Введите адрес электронной почты...')
                -> type('email')
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
            Input::make('person.corp_email')
                -> title('Корпоративный адрес электронной почты')
                -> placeholder('Введите адрес электронной почты...')
                -> help('Назначается самой системой при выдаче корпоративных учетных данных.')
                -> type('email')
                -> readonly(),
            Input::make('person.tel')
                -> title('Номер телефона')
                -> placeholder('Введите номер телефона...')
                -> type('tel')
                -> mask([
                    'mask' => '+7 (999) 999 99-99',
                ])
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
        ];
    }
}
