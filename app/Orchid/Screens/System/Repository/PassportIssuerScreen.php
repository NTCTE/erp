<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\PassportIssuer;
use App\Orchid\Layouts\System\Repository\PassportIssuerTable;
use Orchid\Screen\Screen;

class PassportIssuerScreen extends Screen
{
    public $issuer;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PassportIssuer $issuer): iterable
    {
        return [
            'issuer' => $issuer::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Места выдачи паспортов';
    }

    public function description(): ?string
    {
        return 'Обычно, отсюда работать не нужно, таблица "Места выдачи паспортов" заполняется автоматически. Если есть жизненная необходимость, то добавьте отсюда. Но зачем?';
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
            PassportIssuerTable::class,
        ];
    }
}
