<?php

namespace App\Orchid\Screens\System\Repository;

use Orchid\Screen\Screen;

class DocumentSchemaScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Схемы документов';
    }

    public function description(): ?string
    {
        return 'Таблица со схемами документов, которые можно привязать к пользователю. Удалить схему нельзя, можно только перевести ее в режим "только для чтения"!';
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
        return [];
    }
}
