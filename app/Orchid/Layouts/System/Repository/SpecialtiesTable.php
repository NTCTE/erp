<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\Specialty;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SpecialtiesTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'specialties';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('formatted', 'Специальность'),
            TD::make('actions', 'Действия')
                -> render(function(Specialty $specialty) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Редактировать')
                                -> icon('pencil')
                                -> method('create')
                                -> modal('addSpecialtyModal')
                                -> modalTitle('Редактирование специальности')
                                -> asyncParameters([
                                    'id' => $specialty -> id,
                                    'code' => $specialty -> code,
                                    'fullname' => $specialty -> fullname,
                                ]),
                            Button::make('Удалить специальность')
                                -> icon('trash')
                                -> confirm("Вы уверены, что хотите удалить специальность {$specialty -> formatted}?")
                                -> method('delete', [
                                    'id' => $specialty -> id,
                                ]),
                        ]);
                }),
        ];
    }
}
