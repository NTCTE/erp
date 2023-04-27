<?php

namespace App\Orchid\Layouts\Library;

use App\Models\Org\Library\Actions\TakenInstance;
use App\Models\Org\Library\Instance;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class InstanceIssuanceTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'takenInstances';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('instance_id', 'Экземпляр (инв. №)')
                ->render(function (TakenInstance $takenInstance) {
                    return $takenInstance->instances->inventory_number;
                }),
            TD::make('person_id', 'Персона')
                ->render(function (TakenInstance $takenInstance) {
                    return $takenInstance->persons->getFullnameAttribute();
                }),
            TD::make('deadline', 'Срок сдачи'),
            TD::make('return_date', 'Дата сдачи'),
            TD::make('actions', 'Действия')
                ->render(function (TakenInstance $takenInstance) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            ModalToggle::make('Изменить')
                                ->icon('pencil')
                                ->modal('issuanceModal')
                                ->modalTitle('Изменить данные о выданном экземпляре')
                                ->method('issuance')
                                ->asyncParameters($takenInstance)
                        ]);
                })
        ];
    }
}
