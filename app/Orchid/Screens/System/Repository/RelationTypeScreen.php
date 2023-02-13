<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\RelationType;
use App\Orchid\Layouts\System\Repository\RelationTypeTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

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
        return [
            ModalToggle::make('Добавить')
                -> modal('relationTypeCreate')
                -> method('create')
                -> icon('plus'),
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
            Layout::modal('relationTypeCreate', [
                Layout::rows([
                    Input::make('fullname')
                        -> title('Название родственной связи')
                        -> required()
                        -> placeholder('Например: "Супруг"'),
                ]),
            ])
                -> title('Добавить родственную связь')
                -> applyButton('Добавить')
                -> withoutCloseButton(),
            RelationTypeTable::class,
        ];
    }

    public function create(RelationType $rel_type)
    {
        $rel_type -> fill(request() -> all());
        $rel_type -> save();

        Toast::success('Родственная связь успешно добавлена.');
    }
}
