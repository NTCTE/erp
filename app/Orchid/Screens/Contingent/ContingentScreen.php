<?php

namespace App\Orchid\Screens\Contingent;

use App\Models\Org\Contingent\Person;
use App\Orchid\Layouts\Contingent\ContingentTable;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class ContingentScreen extends Screen
{
    public $persons;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Person $persons): iterable
    {
        return [
            'persons' => $persons::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Контингент';
    }

    public function description(): ?string
    {
        return 'Тут представлен контингент персон, зарегистрированных в системе. Тут все: родители, родственники, студенты, работники и все-все-все.';
    }
    public function permission(): ?iterable
    {
        return [
            'org.contingent.*',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить персону')
                -> icon('plus')
                -> route('org.contingent.person')
                -> permission('org.contingent.write'),
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
            ContingentTable::class,
        ];
    }
}
