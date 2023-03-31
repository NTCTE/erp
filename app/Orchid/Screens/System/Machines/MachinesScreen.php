<?php

namespace App\Orchid\Screens\System\Machines;

use App\Models\System\Machines\Machine;
use App\Orchid\Layouts\System\Machines\Tables\MachinesTable;
use Orchid\Screen\Screen;

class MachinesScreen extends Screen
{
    public $machines;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Machine $machine): iterable
    {
        return [
            'machines' => $machine -> paginate(),
        ];
    }

    public function permission(): array {
        return [
            'platform.systems.machines',
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Зарегистрированные машины';
    }

    public function description(): ?string
    {
        return 'Перечень машин, на которые рассылаются команды. Машины обязаны регистрироваться самостоятельно.';
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
            MachinesTable::class,
        ];
    }
}
