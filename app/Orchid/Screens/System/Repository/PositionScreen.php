<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\Position;
use App\Orchid\Layouts\System\Repository\PositionTable;
use Orchid\Screen\Screen;

class PositionScreen extends Screen
{
    public $position;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Position $position): iterable
    {
        return [
            'position' => $position::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Должности';
    }

    public function description(): ?string
    {
        return 'Обычно, отсюда работать не нужно, таблица "Должности" заполняется автоматически. Если есть жизненная необходимость, то добавьте отсюда. Но зачем?';
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
            PositionTable::class,
        ];
    }
}
