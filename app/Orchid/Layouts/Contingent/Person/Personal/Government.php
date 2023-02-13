<?php

namespace App\Orchid\Layouts\Contingent\Person\Personal;

use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class Government extends Rows
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
            Input::make('person.snils')
                -> title('СНИЛС')
                -> placeholder('Введите СНИЛС...')
                -> mask([
                    'numericInput' => true,
                    'mask' => '999-999-999 99',
                ])
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
            Input::make('person.inn')
                -> title('ИНН')
                -> placeholder('Введите ИНН...')
                -> mask([
                    'numericInput' => true,
                    'mask' => '999999999999',
                ])
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
            Input::make('person.hin')
                -> title('Полис ОМС')
                -> placeholder('Введите номер полиса ОМС...')
                -> mask([
                    'numericInput' => true,
                    'mask' => '9999-9999-9999-9999',
                ])
                -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
        ];
    }
}
