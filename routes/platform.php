<?php

declare(strict_types=1);

use App\Models\System\Repository\Repository as RepositoryRepository;
use App\Orchid\Screens\Contingent\ContingentScreen;
use App\Orchid\Screens\Contingent\Person\AddEditScreen;
use App\Orchid\Screens\Contingent\Person\Documents\AddRelationScreen;
use App\Orchid\Screens\Contingent\Person\Documents\EditPassportScreen;
use App\Orchid\Screens\EdPart\Departments\DepartmentScreen;
use App\Orchid\Screens\EdPart\Departments\MainScreen;
use App\Orchid\Screens\EdPart\Schedule\Legacy\Add;
use App\Orchid\Screens\EdPart\Schedule\Legacy\Edit;
use App\Orchid\Screens\EdPart\Schedule\Legacy\FullList;
use App\Orchid\Screens\Library\BookSetLegendScreen;
use App\Orchid\Screens\Library\BookSetScreen;
use App\Orchid\Screens\Library\EditBooksetScreen;
use App\Orchid\Screens\Library\LibraryScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\System\Repository;
use App\Orchid\Screens\System\Repository\EditLanguageScreen;
use App\Orchid\Screens\System\Repository\NewDocumentSchemaScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tabuna\Breadcrumbs\Trail;
use App\Orchid\Screens\EdPart\Departments\Groups\MainScreen as GroupsMainScreen;
use App\Orchid\Screens\EdPart\Departments\Groups\Students\ActionsScreen;
use App\Orchid\Screens\EdPart\Departments\Groups\Students\JobScreen;
use App\Orchid\Screens\System\Machines\CommandsScreen;
use App\Orchid\Screens\System\Machines\ExecutedScreen;
use App\Orchid\Screens\System\Machines\MachinesScreen;

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
    ->name('schedule.legacy')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('platform.index')
            ->push('Расписание (старое)', route('schedule.legacy'));
    });
// Platform > Schedule LEGACY List > Edit
Route::screen('schedule/legacy/list/{list}', Edit::class)
    ->name('schedule.legacy.item')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('schedule.legacy');
    });
// Platform > Schedule LEGACY List > Add
Route::screen('schedule/legacy/add', Add::class)
    ->name('schedule.legacy.add')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('schedule.legacy')
            ->push('Добавить', route('schedule.legacy.add'));
    });

// SYSTEM
// System > Repository
Route::screen('/system/repository', Repository::class)
    ->name('system.repository')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('platform.index')
            ->push('Система')
            ->push('Репозиторий', 'system.repository');
    });
// System > Repository > Entities
if (Schema::hasTable('repository'))
    foreach (RepositoryRepository::all() as $entity) {
        Route::screen("/system/repository/{$entity['uri']}", $entity['class_type'])
            ->name($entity['path'])
            ->breadcrumbs(function (Trail $trail) use ($entity) {
                return $trail->parent('system.repository')
                    ->push($entity['name'], route($entity['path']));
            });
    }

// System > Repository > Documents Schema > Add Item
Route::screen('/system/repository/document-schemas/add', NewDocumentSchemaScreen::class)
    ->name('system.repository.documentSchemas.add')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('system.repository.documentSchemas')
            ->push('Добавить схему', route('system.repository.documentSchemas.add'));
    });

// System > Repository > Language > Add
Route::screen('/system/repository/language/{language?}', EditLanguageScreen::class)
    ->name('system.repository.language.edit')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('system.repository.languages')
            ->push('Редактировать язык', route('system.repository.language.edit'));
    });

// CONTINGENT
// System > Org > Contingent
Route::screen('/org/contingent', ContingentScreen::class)
    ->name('org.contingent')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('platform.index')
            ->push('Организация')
            ->push('Контингент', route('org.contingent'));
    });

// System > Org > Contingent > Person
Route::screen('/org/contingent/person/{id?}', AddEditScreen::class)
    ->name('org.contingent.person')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('org.contingent')
            ->push('Персона');
    });

// System > Org > Contingent > Person > Add New Document
Route::screen('/org/contingent/person/{id}/document/{type}/{doc_id?}', AddRelationScreen::class)
    ->name('org.contingent.person.document')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('org.contingent.person')
            ->push('Добавить новый документ');
    });

// System > Org > Contingent > Person > Edit Passport
Route::screen('/org/contingent/person/{id}/passport/{passport_id}', EditPassportScreen::class)
    ->name('org.contingent.person.passport')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('org.contingent.person')
            ->push('Редактировать паспорт');
    });

// DEPARTMENTS
//System > Org > Departments
Route::screen('/org/departments', MainScreen::class)
    ->name('org.departments')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('platform.index')
            ->push('Организация')
            ->push('Отделения', route('org.departments'));
    });

// System > Org > Departments > Entity
Route::screen('/org/departments/entity/{department?}', DepartmentScreen::class)
    ->name('org.departments.entity')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('org.departments')
            ->push('Отделение', route('org.departments.entity', request()->route()->parameter('department')));
    });

// System > Org > Departments > Groups > Entity
Route::screen('/org/departments/{department}/group/{group?}', GroupsMainScreen::class)
    ->name('org.departments.group')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('org.departments.entity')
            ->push('Группа', route('org.departments.group', [
                'department' => request()->route('department'),
                'group' => request()->route('group')
            ]));
    });

// System > Org > Departments > Groups > Entity > Student > Jobs
Route::screen('/org/departments/{department}/group/{group}/{student}/jobs/{jobs}', JobScreen::class)
    ->name('org.departments.group.student.jobs')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('org.departments.group')
            ->push('Студент')
            ->push('Действия над студентом');
    });

// LIBRARY
Route::screen('library/booksets', BookSetScreen::class)
    ->name('library.booksets')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('platform.index')
            ->push('Комплекты книг', route('library.booksets'));
    });

// LIBRARY > New Book Set
Route::screen('library/bookset/{bookset?}', EditBooksetScreen::class)
    ->name('library.bookset.edit')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('library.booksets')
            ->push('Работа над комплектом', route('library.bookset.edit'));
    });

// LIBRARY > Book Sets > Legend (Info about Book Set)
Route::screen('library/bookset/info/{bookset?}', BookSetLegendScreen::class)
    ->name('library.bookset.info')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('library.booksets')
            ->push('Работа над комплектом', route('library.bookset.edit'));
    });

// MACHINES
// System > Machines
Route::screen('/system/machines', MachinesScreen::class)
    ->name('system.machines')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('platform.index')
            ->push('Машины');
    });

// System > Machines > Commands
Route::screen('/system/machines/commands', CommandsScreen::class)
    ->name('system.machines.commands')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('platform.index')
            ->push('Машины')
            ->push('Команды', route('system.machines.commands'));
    });

// System > Machines > Executed Commands
Route::screen('/system/machines/executed/{machine}', ExecutedScreen::class)
    ->name('system.machines.executed')
    ->breadcrumbs(function (Trail $trail) {
        return $trail->parent('platform.index')
            ->push('Машины')
            ->push('Выполненные команды', route('system.machines.executed', request()
                ->route()
                ->parameter('machine')));
    });
