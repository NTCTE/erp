<?php

namespace App\Orchid\Layouts\Account;

use App\Models\Org\Library\Actions\TakenInstance;
use App\Models\Org\Library\BookSet;
use App\Models\Org\Library\Instance;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BookTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'takenInstance';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */

    protected function columns(): iterable
    {
        return [
            TD::make('title', 'Название')
                ->render(function (TakenInstance $takenInstance) {
                    return $takenInstance->instances->bookSet->title;
                }),
            TD::make('instance_id', 'Экземпляр (инв. №)')
                ->render(function (TakenInstance $takenInstance) {
                    return $takenInstance->instances->inventory_number;
                }),
            TD::make('deadline', 'Срок сдачи')
                ->render(function (TakenInstance $takenInstance) {
                    return $takenInstance->deadline;
                }),
        ];
    }
}
