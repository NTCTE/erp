<?php

namespace App\Orchid\Layouts\System\Repository\Library;

use App\Models\Org\Library\Additionals\SubjectHeadline;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SubjectHeadlineTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'subjectHeadlines';

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
                -> render(function (SubjectHeadline $subjectHeadline) {
                    return DropDown::make()
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить')
                                -> icon('pencil')
                                -> modal('subjectHeadlineModal')
                                -> modalTitle('Изменение информацию о предметном заголовке')
                                -> method('create')
                                -> asyncParameters($subjectHeadline),
                        ]);
                }),
        ];
    }
}
