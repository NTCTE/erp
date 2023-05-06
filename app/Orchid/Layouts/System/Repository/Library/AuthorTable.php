<?php

namespace App\Orchid\Layouts\System\Repository\Library;

use App\Models\Org\Library\Additionals\Author;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class AuthorTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'authors';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'Автор')
            ->render(function (Author $author) {
                return $author->getFullnameAttribute();
            }),
            TD::make('actions', 'Действия')
                -> render(function (Author $author) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить')
                                -> icon('pencil')
                                -> modal('authorModal')
                                -> modalTitle('Изменение информации об авторе')
                                -> method('create')
                                -> asyncParameters($author),
                        ]);
                }),
        ];
    }
}
