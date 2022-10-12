<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\ProfilePasswordLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class UserProfileScreen extends Screen
{
    /**
     * Query data.
     *
     * @param Request $request
     *
     * @return array
     */
    public function query(Request $request): iterable
    {
        return [
            'user' => $request->user(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Моя учетная запись';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Вы можете проверить свои данные, а также вы можете сменить свой пароль. Остальные данные меняются только через администратора, либо через Отдел кадров.';
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(Layout::legend('user', [
                Sight::make('name', 'ФИО'),
                Sight::make('email', 'Адрес электронной почты'),
            ]))
                -> title('Основная информация учетной записи')
                -> description('Вы можете ознакомиться с основной информацией о вас. Если вы обнаружили ошибку, то нужно сообщить администратору системы, либо в Отдел кадров.'),

            Layout::block(ProfilePasswordLayout::class)
                -> title('Обновить пароль')
                -> description('В целях безопасности, рекомендуется менять пароль не менее, чем раз в полгода. Рекомендуется использовать длинный пароль, который содержит цифры, знаки.')
                -> commands(
                    Button::make('Обновить пароль')
                        -> type(Color::DEFAULT())
                        -> icon('check')
                        -> method('changePassword')
                ),
        ];
    }

    /**
     * @param Request $request
     */
    public function changePassword(Request $request): void
    {
        $guard = config('platform.guard', 'web');
        $request->validate([
            'old_password' => 'required|current_password:'.$guard,
            'password'     => 'required|confirmed',
        ]);

        tap($request->user(), function ($user) use ($request) {
            $user->password = Hash::make($request->get('password'));
        })->save();

        Toast::info(__('Password changed.'));
    }
}
