<?php

namespace App\Orchid\Layouts\Library;

use App\Models\Org\Library\Instance;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class InstanceTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'instance';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('book_set_id', 'Комплект книг')
                ->render(function (Instance $instance) {
                    return $instance->bookSet->title;
                }),
            TD::make('inventory_number', 'Инвентарный номер'),
            TD::make('rfid_signature', 'Номер RFID-метки'),
            TD::make('actions', 'Действия')
                ->render(function (Instance $instance) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            ModalToggle::make('Изменить')
                                ->icon('pencil')
                                ->modal('instanceModal')
                                ->modalTitle('Изменить данные о экземпляре')
                                ->method('create')
                                ->asyncParameters($instance)
                        ]);
                })
        ];
    }
}
