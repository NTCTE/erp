<?php

namespace App\Orchid\Layouts\Contingent\Person;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class CreateRows extends Rows
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
            Group::make([
                Input::make('person.lastname')
                    -> title('Фамилия')
                    -> placeholder('Введите фамилию...')
                    -> required(),
                Input::make('person.firstname')
                    -> title('Имя')
                    -> placeholder('Введите имя...')
                    -> required(),
                Input::make('person.patronymic')
                    -> title('Отчество (при наличии)')
                    -> placeholder('Введите отчество...'),
            ]),
            Group::make([
                DateTimer::make('person.birthdate')
                    -> title('Дата рождения')
                    -> placeholder('Выберите дату...')
                    -> format('d.m.Y'),
                Select::make('person.sex')
                    -> title('Пол')
                    -> options([
                        0 => 'Мужской',
                        1 => 'Женский',
                    ])
                    -> empty('Выберите пол...'),
            ]),
            Group::make([
                Input::make('person.email')
                    -> title('Адрес электронной почты')
                    -> placeholder('Введите адрес электронной почты...')
                    -> type('email'),
                Input::make('person.tel')
                    -> title('Номер телефона (российский)')
                    -> placeholder('Введите номер телефона...')
                    -> type('tel')
                    -> mask([
                        'mask' => '+7 (999) 999 99-99',
                    ]),
            ]),
            Group::make([
                Input::make('person.snils')
                    -> title('СНИЛС')
                    -> placeholder('Введите СНИЛС...')
                    -> mask([
                        'numericInput' => true,
                        'mask' => '999-999-999 99',
                    ]),
                Input::make('person.inn')
                    -> title('ИНН')
                    -> placeholder('Введите ИНН...')
                    -> mask([
                        'numericInput' => true,
                        'mask' => '999999999999',
                    ]),
                Input::make('person.hin')
                    -> title('Полис ОМС')
                    -> placeholder('Введите номер полиса ОМС...')
                    -> mask([
                        'numericInput' => true,
                        'mask' => '9999-9999-9999-9999',
                    ]),
            ]),
        ];
    }
}
