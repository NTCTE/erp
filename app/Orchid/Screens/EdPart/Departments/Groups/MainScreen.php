<?php

namespace App\Orchid\Screens\EdPart\Departments\Groups;

use App\Models\Org\EdPart\Departments\Department;
use App\Models\Org\EdPart\Departments\Group;
use App\Models\System\Relations\AdministativeDocumentsLinks;
use App\Models\System\Repository\AdministrativeDocument;
use App\Orchid\Layouts\EdPart\Departments\Groups\Rows\OrderListener;
use App\Orchid\Layouts\EdPart\Departments\Groups\Rows\InformationRows;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class MainScreen extends Screen
{
    public $group;
    public $department;
    public $students;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $group = Group::find(request() -> route() -> parameter('group'));
        return [
            'department' => Department::find(request() -> route() -> parameter('department')),
            'group' => $group,
            'students' => !is_null($group) ? $group
                -> students()
                -> where('is_academic_leave', false)
                -> paginate() : null,
            'academic_leave' => !is_null($group) ? $group
                -> students()
                -> where('is_academic_leave', true)
                -> paginate() : null,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return !is_null($this -> group) ? "Редактирование группы {$this -> group -> name()}" : 'Создание группы';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Создать')
                -> icon('save')
                -> method('save')
                -> canSee(is_null($this -> group)),
            Button::make('Обновить')
                -> icon('refresh')
                -> method('refresh')
                -> confirm('Вы уверены, что хотите обновить данные группы?')
                -> canSee(!is_null($this -> group)),
        ];
    }

    public function asyncUpdateAppendGroup($async): array {
        $ret = [
            'async.existing_order_cb' => $async['existing_order_cb'],
        ];

        if (!empty($async['existing_order']))
            $ret['async.existing_order'] = $async['existing_order'];

        return $ret;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $layout = [
            InformationRows::class,
            OrderListener::class,
        ];

        return $layout;
    }

    public function save() {
        $group = Group::create(request() -> input('group'));

        if (request() -> input('async.existing_order_cb')) {
            AdministativeDocumentsLinks::create([
                'administrative_document_id' => request() -> input('order.existing_order_id'),
                'signed_id' => $group -> id,
                'signed_type' => Group::class,
            ]);
        } else {
            AdministativeDocumentsLinks::create([
                'administrative_document_id' => AdministrativeDocument::create(request() -> input('order')) -> id,
                'signed_id' => $group -> id,
                'signed_type' => Group::class,
            ]);
        }

        Toast::success('Группа успешно создана');

        return redirect() -> route('org.departments.group', [
            'department' => request() -> route() -> parameter('department'),
            'group' => $group -> id,
        ]);
    }
}
