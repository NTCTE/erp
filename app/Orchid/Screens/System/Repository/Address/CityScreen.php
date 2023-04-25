<?php

namespace App\Orchid\Screens\System\Repository\Address;

use App\Models\System\Repository\Address\City;
use App\Models\System\Repository\Address\Region;
use App\Orchid\Layouts\Selections\CitySelection;
use App\Orchid\Layouts\System\Repository\CitiesTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CityScreen extends Screen
{
    /**
     * @var
     */

    public $cities;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(City $cities): iterable
    {
        return [
            'cities' => $cities
//                -> filtersApplySelection(CitySelection::class)
                -> filters()
                -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Города';
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Репозиторий городов.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить город')
            ->modal('cityModal')
            ->method('create')
            ->icon('plus')
            ->modalTitle('Добавить новый город')
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
            Layout::modal('cityModal', [
                Layout::rows([
                    Input::make('city.fullname')
                        ->title('Название города')
                        ->placeholder('Введите название города')
                        ->required(),
                    Relation::make('city.region_id')
                        ->fromModel(Region::class, 'fullname')
                        ->title('Выберите регион')
                        ->required(),
                    Input::make('city.id')
                        -> type('hidden'),
                ]),
            ])
                ->withoutCloseButton()
                ->applyButton('Сохранить')
                ->staticBackdrop()
                ->async('asyncGetCity'),
            CitiesTable::class,
//            CitySelection::class,
        ];
    }
    public function asyncGetCity(array $fields = null): array {
        return is_null($fields) ? [] : [
            'city' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('city');
        if ($city = City::find($get['id']))
            $city -> update($get);
        else
            City::create($get);

        Toast::info('Данные сохранены');
    }

}
