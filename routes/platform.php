<?php

declare(strict_types=1);

use App\Orchid\Screens\EdPart\Schedule\Legacy\Add;
use App\Orchid\Screens\EdPart\Schedule\Legacy\Edit;
use App\Orchid\Screens\EdPart\Schedule\Legacy\FullList;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\System\Repository;
use App\Orchid\Screens\System\Repository\DocumentSchemaScreen;
use App\Orchid\Screens\System\Repository\PassportIssuerScreen;
use App\Orchid\Screens\System\Repository\PositionScreen;
use App\Orchid\Screens\System\Repository\RelationTypeScreen;
use App\Orchid\Screens\System\Repository\WorkplaceScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profile'), route('platform.profile'));
    });

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(function (Trail $trail, $user) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('User'), route('platform.systems.users.edit', $user));
    });

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.users')
            ->push(__('Create'), route('platform.systems.users.create'));
    });

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Users'), route('platform.systems.users'));
    });

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(function (Trail $trail, $role) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Role'), route('platform.systems.roles.edit', $role));
    });

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.systems.roles')
            ->push(__('Create'), route('platform.systems.roles.create'));
    });

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Roles'), route('platform.systems.roles'));
    });

// Screens of ERP system
// SCHEDULE LEGACY
// Platform > Schedule LEGACY List
Route::screen('schedule/legacy', FullList::class)
    -> name('schedule.legacy')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('platform.index')
            -> push('Расписание (старое)', route('schedule.legacy'));
    });
// Platform > Schedule LEGACY List > Edit
Route::screen('schedule/legacy/list/{list}', Edit::class)
    -> name('schedule.legacy.item')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('schedule.legacy');
    });
// Platform > Schedule LEGACY List > Add
Route::screen('schedule/legacy/add', Add::class)
    -> name('schedule.legacy.add')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('schedule.legacy')
            -> push('Добавить', route('schedule.legacy.add'));
    });

// SYSTEM
// System > Repository
Route::screen('/system/repository', Repository::class)
    -> name('system.repository')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('platform.index')
            -> push('Система')
            -> push('Репозиторий', 'system.repository');
    });
// System > Repository > Document Schemas
Route::screen('/system/repository/document-schemas', DocumentSchemaScreen::class)
    -> name('system.repository.documentSchemas')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('system.repository')
            -> push('Схемы документов', route('system.repository.documentSchemas'));
    });
// System > Repository > Passport Issuers
Route::screen('/system/repository/passport-issuers', PassportIssuerScreen::class)
    -> name('system.repository.passportIssuers')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('system.repository')
            -> push('Места выдачи паспорта', route('system.repository.passportIssuers'));
    });
// System > Repository > Positions
Route::screen('/system/repository/positions', PositionScreen::class)
    -> name('system.repository.positions')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('system.repository')
            -> push('Должности', route('system.repository.positions'));
    });
// System > Repository > Relation Types
Route::screen('/system/repository/relation-types', RelationTypeScreen::class)
    -> name('system.repository.relationTypes')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('system.repository')
            -> push('Родственные связи', route('system.repository.relationTypes'));
    });
// System > Repository > Workplaces
Route::screen('/system/repository/workplaces', WorkplaceScreen::class)
    -> name('system.repository.workplaces')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('system.repository')
            -> push('Родственные связи', route('system.repository.workplaces'));
    });