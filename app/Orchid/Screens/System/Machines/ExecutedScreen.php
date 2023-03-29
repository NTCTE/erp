<?php

namespace App\Orchid\Screens\System\Machines;

use App\Models\System\Machines\Machine;
use App\Orchid\Layouts\System\Machines\Tables\ExecutedTable;
use Orchid\Screen\Screen;

class ExecutedScreen extends Screen
{
    public $machine;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Machine $machine): iterable
    {
        return [
            'machine' => $machine,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Список выполненных команд';
    }

    public function description(): ?string
    {
        return "Список команд, которые были выполнены на машине с IP-адресом {$this -> machine -> ip_address}.";
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
            ExecutedTable::class,
        ];
    }
}
