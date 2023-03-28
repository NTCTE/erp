<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\Address\Country;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CountriesTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'countries';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Страна'),
            TD::make('actions', 'Действия')
                ->render(function (Country $country) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            ModalToggle::make('Изменить')
                                ->icon('pencil')
                                ->modal('countryModal')
                                ->modalTitle('Изменить данные о стране')
                                ->method('create')
                                ->asyncParameters($country),
//                            Button::make('Удалить страну')
//                                 -> icon('trash')
//                                 -> confirm("Вы уверены, что хотите удалить страну {$country -> fullname}?")
//                                 -> method('delete', [
//                                     'id' => $country -> id,
//                                 ]),
                        ]);

                }),

        ];
    }
}
