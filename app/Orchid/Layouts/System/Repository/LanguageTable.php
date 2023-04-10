<?php

namespace App\Orchid\Layouts\System\Repository;

use App\Models\System\Repository\Language;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LanguageTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'language';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Название языка')
                ->render(function (Language $language) {
                        return Link::make($language->fullname)
                            ->route('system.repository.language.edit', $language);
                })
        ];
    }
}
