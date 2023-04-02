<?php

namespace App\Orchid\Screens\Library;

use App\Models\Org\Library\BookSet;
use App\Orchid\Layouts\Library\LibraryTable;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class LibraryScreen extends Screen
{
    public $booksets;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(BookSet $bookset): iterable
    {
        return [
            'booksets' => BookSet::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Библиотека | Наборы книг';
    }

    public function description(): ?string
    {
        return 'Добро пожаловать в библиотечную систему! Отсюда вы можете взаимодействовать с наборами книг.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить набор')
                ->icon('plus')
                ->route('org.library.bookset.edit')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            LibraryTable::class,
        ];
    }
}
