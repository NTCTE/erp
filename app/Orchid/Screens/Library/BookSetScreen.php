<?php

namespace App\Orchid\Screens\Library;

use App\Models\Org\Library\BookSet;
use App\Orchid\Layouts\Library\BookSetTable;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class BookSetScreen extends Screen
{
    public $bookset;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(BookSet $bookSets): iterable
    {
        return [
            'booksets' => $bookSets::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Наборы книг';
    }

    public function description(): ?string
    {
        return 'Список наборов книг';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить')
                ->icon('plus')
                ->route('library.bookset.edit')
                ->canSee(Auth::user()->hasAccess('library.write')),
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
            BookSetTable::class,
        ];
    }
}
