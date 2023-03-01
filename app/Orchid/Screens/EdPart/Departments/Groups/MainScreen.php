<?php

namespace App\Orchid\Screens\EdPart\Departments\Groups;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Department;
use App\Models\Org\EdPart\Departments\Group;
use Carbon\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Str;
use Orchid\Support\Facades\Toast;

class MainScreen extends Screen
{
    public $group;
    public $department;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'department' => Department::find(request() -> route() -> parameter('department')),
            'group' => Group::find(request() -> route() -> parameter('group')),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return !is_null($this -> group) ? 'Редактирование группы' : 'Создание группы';
    }

    public function description(): ?string
    {
        return is_null($this -> group) ? null : "Группа {$this -> group -> name()}";
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

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('group.shortname')
                    -> title('Краткое имя группы')
                    -> placeholder('Введите краткое имя группы...')
                    -> help('Например, ИС-3, ИС-6, ИС-9. Номер курса не указывается, он будет добавлен автоматически. Вставьте симовол "#" там, где нужно вставить номер курса.')
                    -> required()
                    -> horizontal(),
                DateTimer::make('group.enrollment_date')
                    -> title('Дата зачисления')
                    -> placeholder('Введите дату зачисления...')
                    -> format('d.m.Y')
                    -> required()
                    -> horizontal(),
                Input::make('group.training_period')
                    -> title('Срок обучения')
                    -> placeholder('Введите срок обучения...')
                    -> help('Вводите полное число лет. Если срок обучения составляет 3 года и 10 месяцев, то введите 4.')
                    -> mask([
                        'alias' => 'integer',
                        'numericInput' => true,
                    ])
                    -> required()
                    -> horizontal(),
                Relation::make('group.department_id')
                    -> title('Отделение')
                    -> placeholder('Выберите отделение...')
                    -> value($this -> department -> id)
                    -> fromModel(Department::class, 'fullname', 'id')
                    -> required()
                    -> horizontal(),
                Relation::make('group.curator_id')
                    -> title('Куратор')
                    -> placeholder('Выберите куратора...')
                    -> fromModel(Person::class, 'lastname', 'id')
                    -> searchColumns('firstname', 'patronymic')
                    -> displayAppend('fullname')
                    -> required()
                    -> horizontal(),
                CheckBox::make('group.archived')
                    -> title('Архивная группа')
                    -> help('Если группа является архивной, то она не будет отображаться в списке активных групп. Все архивные группы можно посмотреть в разделе "Архив групп" на экране соответствующего отделения. Архивация группы происходит автоматически, когда группа заканчивает свой срок обучения.')
                    -> sendTrueOrFalse()
                    -> horizontal(),
            ]),
        ];
    }

    public function save() {
        $group = request() -> input('group');
        $group['enrollment_date'] = Carbon::createFromFormat('d.m.Y', $group['enrollment_date']) -> format('Y-m-d');
        $group['uuid'] = Str::uuid();

        Toast::success('Группа успешно создана!');

        return redirect() -> route('org.departments.group', [
            'department' => $group['department_id'],
            'group' => Group::create($group) -> id,
        ]);
    }

    public function refresh() {
        $group = request() -> input('group');
        $group['enrollment_date'] = Carbon::createFromFormat('d.m.Y', $group['enrollment_date']) -> format('Y-m-d');

        Group::find(request() -> route() -> parameter('group'))
            -> update($group);

        Toast::success('Данные группы успешно обновлены!');

        return redirect() -> route('org.departments.group', [
            'department' => $group['department_id'],
            'group' => request() -> route() -> parameter('group'),
        ]);
    }
}
