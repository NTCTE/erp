<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\SocialStatus;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SocialStatusesTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'statuses';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Название'),
            TD::make('actions', 'Действия')
                -> render(function(SocialStatus $status) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        ->list([
                            ModalToggle::make('Редактировать')
                                -> icon('pencil')
                                -> method('create')
                                -> modal('socialStatusModal')
                                -> modalTitle('Редактирование социального статуса')
                                -> asyncParameters([
                                    'id' => $status -> id,
                                    'fullname' => $status -> fullname,
                                ]),
                            Button::make('Удалить запись')
                                -> icon('trash')
                                -> confirm("Вы уверены, что хотите удалить социальный статус \"{$status -> fullname}\"?")
                                -> method('delete', [
                                    'id' => $status -> id,
                                ]),
                        ]);
                }),
        ];
    }
}
