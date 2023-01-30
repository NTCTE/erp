<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\Repository;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RepositoryTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'repository';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Имя таблицы')
                -> render(function(Repository $repository) {
                    return Link::make($repository -> name)
                        -> route($repository -> path);
                }),
        ];
    }
}
