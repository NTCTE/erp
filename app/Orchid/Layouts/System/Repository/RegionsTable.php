<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\Address\Country;
use App\Models\System\Repository\Address\Region;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RegionsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'regions';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Регион'),
            TD::make('country_id', 'Страна')
                ->render(function (Region $region) {
                    return $region->country->fullname;
                }),
            TD::make('actions', 'Действия')
                ->render(function (Region $region) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            ModalToggle::make('Изменить')
                                ->icon('pencil')
                                ->modal('regionModal')
                                ->modalTitle('Изменить данные о регионе')
                                ->method('create')
                                ->asyncParameters($region)
                        ]);
                }),
        ];
    }
}
