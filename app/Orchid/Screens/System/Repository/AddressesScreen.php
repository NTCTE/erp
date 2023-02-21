<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\Address;
use App\Orchid\Layouts\System\AddressListener;
use App\Orchid\Layouts\System\Repository\AddressesTable;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class AddressesScreen extends Screen
{
    public $addresses;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Address $address): iterable
    {
        return [
            'addresses' => $address -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Адреса';
    }

    public function description(): ?string
    {
        return 'Репозиторий адресов. Обычно, данные в этом репозитории заполняются через соотвествующие формы. На данном окне вы не можете изменить данные, только просмотреть.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
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
            AddressesTable::class,
        ];
    }

    public function asyncAddressPromt(string $address) {

        return [
            'address_promt' => $address,
        ];
    }
}
