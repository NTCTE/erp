<?php

namespace App\Orchid\Screens\Account;

use App\Models\Org\Library\Actions\TakenInstance;
use App\Orchid\Layouts\Account\BookTable;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;

class ReceivedBooksScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'takenInstance' => TakenInstance::query()->where('taken_instances.person_id', '=', Auth::id())-> paginate(),
        ];
    }


    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Полученные книги';
    }

    public function description(): ?string
    {
        return 'Список полученных книг';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            BookTable::class
        ];
    }
}
