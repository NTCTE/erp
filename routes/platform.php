<?php

declare(strict_types=1);

use App\Models\System\Repository\Repository as RepositoryRepository;
use App\Orchid\Screens\Contingent\ContingentScreen;
use App\Orchid\Screens\Contingent\Person\AddEditScreen;
use App\Orchid\Screens\EdPart\Schedule\Legacy\Add;
use App\Orchid\Screens\EdPart\Schedule\Legacy\Edit;
use App\Orchid\Screens\EdPart\Schedule\Legacy\FullList;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\System\Repository;
use App\Orchid\Screens\System\Repository\NewDocumentSchemaScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
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
// System > Repository > Entities
if (Schema::hasTable('repository'))
    foreach (RepositoryRepository::all() as $entity) {
        Route::screen("/system/repository/{$entity['uri']}", $entity['class_type'])
            -> name($entity['path'])
            -> breadcrumbs(function(Trail $trail) use ($entity) {
                return $trail -> parent('system.repository')
                    -> push($entity['name'], route($entity['path']));
            });
    }

// System > Repository > Documents Schema > Add Item
Route::screen('/system/repository/document-schemas/add', NewDocumentSchemaScreen::class)
    -> name('system.repository.documentSchemas.add')
    -> breadcrumbs(function (Trail $trail) {
        return $trail -> parent('system.repository.documentSchemas')
            -> push('Добавить схему', route('system.repository.documentSchemas.add'));
    });

// System > Org > Contingent
Route::screen('/org/contingent', ContingentScreen::class)
    -> name('org.contingent')
    -> breadcrumbs(function(Trail $trail) {
        return $trail -> parent('platform.index')
            -> push('Организация')
            -> push('Контингент', route('org.contingent'));
    });

// System > Org > Contingent > Person
Route::screen('/org/contingent/person/{id?}', AddEditScreen::class)
    -> name('org.contingent.person')
    -> breadcrumbs(function (Trail $trail) {
        return $trail -> parent('org.contingent')
            -> push('Персона');
    });
