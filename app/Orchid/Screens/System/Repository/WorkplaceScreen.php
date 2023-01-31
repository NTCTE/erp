<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\Workplace;
use App\Orchid\Layouts\System\Repository\WorkplaceTable;
use Orchid\Screen\Screen;

class WorkplaceScreen extends Screen
{
    public $workplace;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Workplace $workplace): iterable
    {
        return [
            'workplace' => $workplace::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Рабочие места';
    }

    public function description(): ?string
    {
        return 'Обычно, отсюда работать не нужно, таблица "Рабочие места" заполняется автоматически. Если есть жизненная необходимость, то добавьте отсюда. Но зачем?';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            WorkplaceTable::class,
        ];
    }
}
