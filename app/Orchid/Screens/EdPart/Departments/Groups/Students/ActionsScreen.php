<?php

namespace App\Orchid\Screens\EdPart\Departments\Groups\Students;

use App\Models\System\Relations\StudentsLink;
use App\Orchid\Layouts\EdPart\Departments\Groups\Students\Tables\ActionsTable;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ActionsScreen extends Screen
{
    public $student;
    public $actions;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $student = StudentsLink::find(request() -> student);
        return [
            'student' => $student,
            'actions' => $student -> actions,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return "Студент {$this -> student -> person -> fullname}";
    }

    public function description(): ?string {
        return "Студент группы {$this -> student -> group -> name}. Зарегистрирован в системе как студент {$this -> student -> created_at}.";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
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
        return [
            ActionsTable::class,
        ];
    }
}
