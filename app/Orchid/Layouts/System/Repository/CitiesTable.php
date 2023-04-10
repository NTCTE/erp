<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\Address\City;
use App\Models\System\Repository\Address\Region;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CitiesTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'cities';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Город'),
            TD::make('region_id', 'Регион')
                ->render(function (City $city) {
                    return $city->region->fullname;
                }),
            TD::make('country_id', 'Страна')
                ->render(function (City $city) {
                    return $city->region->country->fullname;
                }),
            TD::make('actions', 'Действия')
                ->render(function (City $city) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            ModalToggle::make('Изменить')
                                ->icon('pencil')
                                ->modal('cityModal')
                                ->modalTitle('Изменить данные о городе')
                                ->method('create')
                                ->asyncParameters($city)
                        ]);
                }),
        ];
    }
}
