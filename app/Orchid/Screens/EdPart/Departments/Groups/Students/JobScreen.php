<?php

namespace App\Orchid\Screens\EdPart\Departments\Groups\Students;

use App\Models\Org\EdPart\Departments\Group;
use App\Models\System\Relations\StudentsLink;
use App\Orchid\Layouts\EdPart\Departments\Groups\Students\Listeners\OrderListener;
use App\Orchid\Layouts\EdPart\Departments\Groups\Students\Tables\ActionsTable;
use App\Traits\System\Listeners\Order;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class JobScreen extends Screen
{
    use Order;

    public $name;
    public $student;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'name' => [
                'move' => 'Перевод студента в другую группу',
                'leave' => 'Перевод студента в академический отпуск',
                'return' => 'Возврат студента из академического отпуска',
                'dismiss' => 'Отчисление студента',
                'history' => 'Действия над студентом',
                'academic_leave_history' => 'История академических отпусков',
            ][request() -> jobs],
            'student' => StudentsLink::find(request() -> student),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this -> name;
    }

    public function description(): ?string {
        return "{$this -> student -> person -> fullname}, студент группы {$this -> student -> group -> name}. Зарегистрирован в системе как студент {$this -> student -> created_at}.";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Сохранить')
                -> icon('save')
                -> method('doAction')
                -> canSee(request() -> jobs != 'history'),
            Link::make('Перейти к персоне')
                -> icon('user')
                -> route('org.contingent.person', $this -> student -> person),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $returned = [];
        switch (request() -> jobs) {
            case 'history':
                $returned[] = ActionsTable::class;
            break;
            case 'move':
                $returned[] = Layout::rows([
                    Relation::make('action.group_id')
                        -> required()
                        -> title('Группа')
                        -> placeholder('Выберите группу...')
                        -> help('Поиск по номеру курса не происходит, вам нужно вводить имя группы без номера курса.')
                        -> fromModel(Group::class, 'shortname')
                        -> displayAppend('name')
                        -> applyScope('archived', false)
                        -> horizontal(),
                    TextArea::make('action.additionals')
                        -> title('Дополнительные сведения')
                        -> rows(5)
                        -> placeholder('Введите дополнительные (внутренние) сведения...')
                        -> help('Эти сведения будут отражены в журнале движения студента.')
                        -> horizontal(),
                ])
                    -> title('Основная информация');
            break;
            case 'leave':
                $returned[] = Layout::rows([
                    TextArea::make('action.reason')
                        -> required()
                        -> title('Причина')
                        -> rows(5)
                        -> placeholder('Введите причину академического отпуска...')
                        -> horizontal(),
                    DateTimer::make('action.expires_at')
                        -> required()
                        -> title('Дата окончания')
                        -> placeholder('Выберите дату окончания академического отпуска...')
                        -> format('d.m.Y')
                        -> horizontal(),
                    TextArea::make('action.additionals')
                        -> title('Дополнительные сведения')
                        -> rows(5)
                        -> placeholder('Введите дополнительные (внутренние) сведения...')
                        -> help('Эти сведения будут отражены в журнале движения студента')
                        -> horizontal(),
                ]);
            break;
            case 'return':
                
            break;
            case 'dismiss':
                
            break;
            default:
                
            break;
        }
        $returned[] = (new OrderListener)
            -> canSee(request() -> jobs != 'history');

        return $returned;
    }
}
