<?php

namespace App\Orchid\Screens\Account;

use App\Models\Org\Contingent\Person;
use App\Models\Org\Library\Actions\TakenInstance;
use App\Models\Org\Library\BookSet;
use App\Models\Org\Library\Instance;
use App\Models\User;
use App\Orchid\Layouts\Account\BookTable;
use App\Orchid\Layouts\Library\InstanceIssuanceTable;
use Carbon\Carbon;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ReceivedBooksScreen extends Screen
{
    public function query(Instance $instance): iterable
    {
        return [
            'instance' => $instance -> paginate(),
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
