<?php

namespace App\Orchid\Screens\System\Machines;

use App\Models\System\Machines\Command;
use App\Orchid\Layouts\System\Machines\Tables\CommandsTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CommandsScreen extends Screen
{
    public $commands;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Command $command): iterable
    {
        return [
            'commands' => $command -> paginate(),
        ];
    }

    public function permission(): array
    {
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
        return 'Команды';
    }

    public function description(): ?string
    {
        return 'Перечень bash-команд, которые рассылаются на машины Linux. ВНИМАНИЕ: все команды выполняются от имени пользователя root!';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Создать команду')
                -> icon('plus')
                -> modal('commandModal')
                -> method('createCommand')
                -> modalTitle('Создание команды'),
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
            Layout::modal('commandModal', [
                Layout::rows([
                    Code::make('command'),
                ]),
            ])
                -> size(Modal::SIZE_LG)
                -> staticBackdrop()
                -> withoutCloseButton()
                -> applyButton('Сохранить'),
            CommandsTable::class,
        ];
    }

    public function createCommand() {
        Command::create([
            'command' => base64_encode(request() -> command),
        ]);

        Toast::info('Ваша команда успешно создана!');
    }
}
