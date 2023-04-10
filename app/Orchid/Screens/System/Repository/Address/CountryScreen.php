<?php

namespace App\Orchid\Screens\System\Repository\Address;

use App\Models\System\Repository\Address\Country;
use App\Orchid\Layouts\System\Repository\CountriesTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use \Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CountryScreen extends Screen
{
    /**
     * @var string
     */
    public $countries;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Country $countries): iterable
    {
        return [
            'countries' => $countries -> paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Страны';
    }

    /**
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Репозиторий стран.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Добавить страну')
                ->modal('countryModal')
                ->method('create')
                ->icon('plus')
                ->modalTitle('Добавить новую страну')
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
            Layout::modal('countryModal', [
                Layout::rows([
                    Input::make('country.fullname')
                        ->title('Название страны')
                        ->placeholder('Введите название страны')
                        ->required(),
                    Input::make('country.id')
                        -> type('hidden'),
                ]),
            ])
            ->withoutCloseButton()
            ->applyButton('Сохранить')
            ->staticBackdrop()
            ->async('asyncGetCountry'),
            CountriesTable::class,
        ];
    }

    public function asyncGetCountry(array $fields = null): array {
        return is_null($fields) ? [] : [
            'country' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('country');
        if ($country = Country::find($get['id']))
            $country -> update($get);
        else
            Country::create($get);

        Toast::info('Данные сохранены');
    }

}
