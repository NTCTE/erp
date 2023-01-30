<?php

namespace App\Orchid\Screens\System;

use App\Models\System\Repository\Repository as RepositoryRepository;
use App\Orchid\Layouts\System\Repository\RepositoryTable;
use Orchid\Screen\Screen;

class Repository extends Screen
{
    public $repository;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(RepositoryRepository $repository): iterable
    {
        return [
            'repository' => $repository::paginate(),
        ];
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
        return [
            RepositoryTable::class,
        ];
    }
}
