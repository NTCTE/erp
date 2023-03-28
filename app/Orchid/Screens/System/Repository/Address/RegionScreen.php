<?php

namespace App\Orchid\Screens\System\Repository\Address;

use App\Models\System\Repository\Address\Country;
use App\Models\System\Repository\Address\Region;
use App\Orchid\Layouts\System\Repository\RegionsTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class RegionScreen extends Screen
{
    /**
     * @var
     */
    public $regions;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Region $regions): iterable
    {
        return [
            'regions' => $regions->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Регионы';
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Репозиторий регионов';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить регион')
                ->modal('regionModal')
                ->method('create')
                ->icon('plus')
                ->modalTitle('Добавить новый регион')
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
            Layout::modal('regionModal', [
                Layout::rows([
                    Input::make('region.fullname')
                        ->title('Название страны')
                        ->placeholder('Введите название страны')
                        ->required(),
                    Relation::make('region.country_id')
                        ->fromModel(Country::class, 'fullname')
                        ->title('Выберите страну')
                        ->required(),
                    Input::make('region.id')
                        ->type('hidden'),
                ]),
            ])
                ->withoutCloseButton()
                ->applyButton('Сохранить')
                ->staticBackdrop()
                ->async('asyncGetRegion'),
            RegionsTable::class,
        ];
    }

    public function asyncGetRegion(array $fields = null): array
    {
        return is_null($fields) ? [] : [
            'region' => $fields,
        ];
    }

    public function create()
    {
        $get = request()->get('region');
        if ($region = Region::find($get['id']))
            $region->update($get);
        else
            Region::create($get);

        Toast::info('Данные сохранены');
    }


}
