<?php

namespace App\Orchid\Layouts\System\Repository\Library;

use App\Models\Org\Library\Additionals\PertainingTitleInformation;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PertainingTitleInformationTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'pertainingTitleInformation';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('formatted', 'Название'),
            TD::make('actions', 'Действия')
                -> render(function (PertainingTitleInformation $pertainingTitleInformation) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить')
                                -> icon('pencil')
                                -> modal('pertainingTitleInformationModal')
                                -> modalTitle('Изменение информации, относящейся к заглавию')
                                -> method('create')
                                -> asyncParameters($pertainingTitleInformation),
                        ]);
                }),
        ];
    }
}
