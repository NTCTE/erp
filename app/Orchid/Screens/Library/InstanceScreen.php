<?php

namespace App\Orchid\Screens\Library;

use App\Models\Org\Library\BookSet;
use App\Models\Org\Library\Instance;
use App\Orchid\Layouts\Library\InstanceTable;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;

class InstanceScreen extends Screen
{
    public $instace;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Instance $instance): iterable
    {
        return [
            'instance' => $instance -> paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Экземпляры';
    }

    public function description(): ?string
    {
        return 'Список экземпляров.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить экземпляр')
                ->modal('instanceModal')
                ->method('create')
                ->icon('plus')
                ->modalTitle('Добавить новый экземпляр')
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
            Layout::modal('instanceModal', [
                Layout::rows([
                    Input::make('instance.id')
                        ->type('hidden'),
                    Relation::make('instance.book_set_id')
                        ->fromModel(BookSet::class, 'title')
                        ->required()
                        ->placeholder('Выберите комплект книг, к которому относится экземпляр'),
                    Input::make('instance.inventory_number')
                        ->title('Инвентарный номер')
                        ->type('text')
                        ->maxlength(100)
                        ->required(),
                    Input::make('instance.rfid_signature')
                        ->title('Номер RFID-метки')
                        ->type('text')
                        ->maxlength(100)
                ])
            ])
            ->withoutCloseButton()
            ->applyButton('Сохранить')
            ->staticBackdrop()
            ->async('asyncGetInstance'),
        InstanceTable::class
        ];
    }

    public function asyncGetInstance(array $fields = null): array {
        return is_null($fields) ? [] : [
            'instance' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('instance');
        if ($instance = Instance::find($get['id'])) {
            $instance -> update($get);
        } else {
            Instance::create($get);
        }
       Toast::info('Данные сохранены');
    }
}
