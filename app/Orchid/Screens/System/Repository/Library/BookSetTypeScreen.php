<?php

namespace App\Orchid\Screens\System\Repository\Library;

use App\Models\Org\Library\Additionals\BookSetType;
use App\Orchid\Layouts\System\Repository\Library\BookSetTypeTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class BookSetTypeScreen extends Screen
{
    public $bookSetTypes;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(BookSetType $bookSetTypes): iterable
    {
        return [
            'bookSetTypes' => $bookSetTypes::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Типы комплектов книг';
    }

    public function description():? string {
        return 'Здесь вы можете добавить тип комплекта книг.';
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
                ->modal('bookSetTypeModal')
                ->method('create')
                ->icon('plus')
                ->modalTitle('Добавить тип набора книг')
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
            Layout::modal('bookSetTypeModal', [
                Layout::rows([
                    Input::make('bookSetType.fullname')
                        ->title('Название')
                        ->placeholder('Введите название типа набора книг')
                        ->required(),
                    Input::make('bookSetType.id')
                        ->type('hidden'),
                ]),
            ])
            ->withoutCloseButton()
            ->applyButton('Сохранить')
            ->staticBackdrop()
            ->async('asyncGetBookSetType'),
            BookSetTypeTable::class,
        ];
    }

    public function asyncGetBookSetType(array $fields = null): array {
        return is_null($fields) ? [] : [
            'bookSetType' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('bookSetType');
        if ($bookSetType = BookSetType::find($get['id']))
            $bookSetType -> update($get);
        else
            BookSetType::create($get);

        Toast::info('Данные сохранены');
    }
}
