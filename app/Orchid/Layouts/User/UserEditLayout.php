<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title('ФИО')
                ->placeholder('Полные фамилия, имя и отчество (при наличии)'),
                // -> canSee(Auth::user() -> hasAccess('platform.system.userEdit')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title('Адрес электронной почты')
                ->placeholder('Корпоративный адрес электронной почты'),
                // -> canSee(Auth::user() -> hasAccess('platform.system.userEdit')),
        ];
    }
}
