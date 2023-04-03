<?php

namespace App\Orchid\Screens\System\Repository\Library;

use App\Models\Org\Library\Additionals\Publisher;
use App\Models\System\Repository\Address\City;
use App\Orchid\Layouts\System\Repository\Library\PublisherTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PublisherScreen extends Screen
{
    public $publishers;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Publisher $publishers): iterable
    {
        return [
            'publishers' => $publishers::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Издатели';
    }

    public function description():? string {
        return 'придумать текст...';
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
                ->modal('publisherModal')
                ->method('create')
                ->icon('plus')
                ->modalTitle('Добавить издателя.')
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
            Layout::modal('publisherModal', [
                Layout::rows([
                    Input::make('publisher.fullname')
                        ->title('Название')
                        ->placeholder('Введите название издательства')
                        ->required(),
                    Relation::make('publisher.city_id')
                        ->fromModel(City::class, 'fullname')
                        ->title('Выберите город нахождения издательства')
                        ->required(),
                    Input::make('publisher.id')
                        ->type('hidden'),
                ])
            ])
                ->withoutCloseButton()
                ->applyButton('Сохранить')
                ->staticBackdrop()
                ->async('asyncGetPublisher'),
            PublisherTable::class,
        ];
    }
    public function asyncGetPublisher(array $fields = null): array {
        return is_null($fields) ? [] : [
            'publisher' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('publisher');
        if ($publisher = Publisher::find($get['id']))
            $publisher -> update($get);
        else
            Publisher::create($get);

        Toast::info('Данные сохранены');
    }
}
