<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\EducationalDocType;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EducationalDocsTypesTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'types';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Наименование'),
            TD::make('readonly', 'Только для чтения')
                -> render(function (EducationalDocType $type) {
                    return $type -> readonly ? 'Да' : 'Нет';
                }),
            TD::make('actions', 'Действия')
                -> render(function (EducationalDocType $type) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            Button::make('Сменить тип чтения')
                                -> method('changeReadonly', [
                                    'id' => $type -> id,
                                ]),
                        ]);
                }),
        ];
    }
}
