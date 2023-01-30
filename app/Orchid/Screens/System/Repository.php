<?php

namespace App\Orchid\Screens\System;

use Orchid\Screen\Screen;

class Repository extends Screen
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
        return 'Репозиторий';
    }
    
    /**
     * Description of screen.
     *
     * @return string
     */
    public function description(): ?string
    {
        return 'На данном окне вы можете изменить редактировать системные таблицы.';
    }
    
    /**
     * Permissions of screen.
     *
     * @return iterable
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.repository',
        ];
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
