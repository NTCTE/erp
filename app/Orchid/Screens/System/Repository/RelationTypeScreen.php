<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\RelationType;
use App\Orchid\Layouts\System\Repository\RelationTypeTable;
use Orchid\Screen\Screen;

class RelationTypeScreen extends Screen
{
    public $rel_type;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(RelationType $rel_type): iterable
    {
        return [
            'rel_type' => $rel_type::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Родственные связи';
    }

    public function description(): ?string
    {
        return 'Список родственных связей, используемых в системе.';
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
            RelationTypeTable::class,
        ];
    }
}
