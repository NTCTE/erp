<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make('Личный кабинет')
            ->icon('star')
            ->list([
                Menu::make('Книги')
                ->icon('barcode')
                ->route('account.books'),
            ]),
            Menu::make('Учебная часть')
                -> icon('organization')
                -> list([
                    Menu::make('Расписание (старое)')
                        -> icon('table')
                        -> route('schedule.legacy')
                        -> permission('edPart.schedule.legacy.add'),
                    Menu::make('Список отделений')
                        -> icon('list')
                        -> route('org.departments')
                        -> permission('org.departments.*'),
                ])
                -> permission('edPart.*'),
            Menu::make('Организация')
                    -> icon('directions')
                    -> permission('org.*')
                    -> list([
                        Menu::make('Контингент')
                            -> icon('friends')
                            -> permission('org.contingent.*')
                            -> route('org.contingent'),
                    ]),
            Menu::make('Библиотека')
                ->icon('book-open')
                ->permission('library.*')
                ->list([
                   Menu::make('Комплекты книг')
                       ->icon('layers')
                       ->permission('library.*')
                       ->route('library.booksets'),
                   Menu::make('Экземпляры')
                       ->icon('doc')
                       ->permission('library.*')
                       ->route('library.instances')
                       ->title('Экземпляры'),
                   Menu::make('Выдача')
                       ->icon('share-alt')
                       ->permission('library.*')
                       ->route('library.instances.issuance')
                ]),
            Menu::make('Система')
                -> icon('config')
                -> list([
                    Menu::make('Репозиторий')
                        -> icon('number-list')
                        -> route('system.repository')
                        -> permission('platform.systems.repository'),
                    Menu::make('Команды')
                        -> icon('linux')
                        -> route('system.machines.commands')
                        -> permission('platform.systems.machines')
                        -> title('Машины'),
                    Menu::make('Машины')
                        -> icon('os')
                        -> route('system.machines')
                        -> permission('platform.systems.machines'),
                    Menu::make(__('Users'))
                        ->icon('user')
                        ->route('platform.systems.users')
                        ->permission('platform.systems.users')
                        ->title(__('Access rights')),
                    Menu::make(__('Roles'))
                        ->icon('lock')
                        ->route('platform.systems.roles')
                        ->permission('platform.systems.roles'),
                ])
                -> permission('platform.systems.*'),
        ];
    }


    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                -> addPermission('platform.systems.roles', __('Roles'))
                -> addPermission('platform.systems.users', __('Users'))
                -> addPermission('platform.systems.repository', 'Репозиторий')
                -> addPermission('platform.systems.machines', 'Машины'),
            ItemPermission::group('Расписание (старое)')
                -> addPermission('edPart.schedule.legacy.add', 'Добавить/изменить расписание'),
            ItemPermission::group('Организация')
                -> addPermission('org.contingent.read', 'Контингент: чтение')
                -> addPermission('org.contingent.write', 'Контингент: запись')
                -> addPermission('org.departments.read', 'Отделения: чтение')
                -> addPermission('org.departments.write', 'Отделения: запись'),
            ItemPermission::group('Библиотека')
                -> addPermission('library.read', 'Библиотека: чтение')
                -> addPermission('library.write', 'Библиотека: запись'),
        ];
    }
}
