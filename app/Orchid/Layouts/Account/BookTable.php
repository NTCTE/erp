<?php

namespace App\Orchid\Layouts\Account;

use App\Models\Org\Library\Actions\TakenInstance;
use Orchid\Screen\Actions\Link;
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
            TD::make('download_book', 'Скачать книгу')
                ->render(function (TakenInstance $takenInstance){
                    $path = $takenInstance->instances->bookSet->digitized()->get()->first()?->url();
                    if ($path === null) {
                        return Link::make('')
                            ->icon('close');
                    } else {
                        return Link::make('')
                            ->icon('arrow-down-circle')
                            ->href($path);
                    }
                })
        ];
    }
}
