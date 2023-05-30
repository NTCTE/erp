<?php

namespace App\Orchid\Screens\Library;

use App\Models\Org\Contingent\Person;
use App\Models\Org\Library\Actions\TakenInstance;
use App\Models\Org\Library\Instance;
use App\Orchid\Layouts\Library\InstanceIssuanceTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class IssuanceScreen extends Screen
{

    public $takenInstances;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(TakenInstance $takenInstances): iterable
    {
        return [
            'takenInstances' => $takenInstances -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Выдача экземпляров';
    }

    public function description(): ?string
    {
        return 'Выдача экземпляров книг персонам.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Выдача')
                ->modal('issuanceModal')
                ->method('issuance')
                ->icon('share-alt')
                ->modalTitle('Выдать экземпляр')
                ->canSee(Auth::user()->hasAccess('library.write'))
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
            Layout::modal('issuanceModal', [
                Layout::rows([
                    Input::make('taken_instance.id')
                        ->type('hidden'),
                    Relation::make('taken_instance.person_id')
                        ->title('Персона')
                        ->fromModel(Person::class, 'lastname')
                        ->displayAppend('Fullname')
                        ->searchColumns('email')
                        ->help('Если в поиске встречаются две одинаковые персоны, используйте поиск по электронной почте.')
                        ->required(),
                    Relation::make('taken_instance.instance_id')
                        ->title('Экземпляр')
                        ->fromModel(Instance::class, 'inventory_number')
                        ->placeholder('Поиск по инвентарному номеру')
                        ->required(),
                    DateTimer::make('taken_instance.deadline')
                        ->title('Срок сдачи')
                        ->format('d.m.Y')
                        ->placeholder('Укажите крайний срок сдачи экземпляра'),
                    DateTimer::make('taken_instance.return_date')
                        ->title('Дата сдачи')
                        ->format('d.m.Y')
                        ->placeholder('Укажите дату, когда экземпляр был возвращён')
                ])
            ])
                ->withoutCloseButton()
                ->applyButton('Выдать')
                ->staticBackdrop()
                ->async('asyncGetTakenInstances'),
            InstanceIssuanceTable::class
        ];
    }

    public function asyncGetTakenInstances(array $fields = null): array {
        return is_null($fields) ? [] : [
            'taken_instance' => $fields,
        ];
    }

    public function issuance() {
        $get = request() -> get('taken_instance');
        if ($takenInstance = TakenInstance::find($get['id']))
            $takenInstance -> update($get);
        else
            TakenInstance::create($get);

        Toast::info('Данные сохранены');
    }
}
