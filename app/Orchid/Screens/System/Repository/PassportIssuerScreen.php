<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\PassportIssuer;
use App\Orchid\Layouts\System\Repository\PassportIssuerTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PassportIssuerScreen extends Screen
{
    public $issuer;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PassportIssuer $issuer): iterable
    {
        return [
            'issuer' => $issuer::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Места выдачи паспортов';
    }

    public function description(): ?string
    {
        return 'Обычно, отсюда работать не нужно, таблица "Места выдачи паспортов" заполняется автоматически. Если есть жизненная необходимость, то добавьте отсюда. Но зачем?';
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
                -> modal('passportIssuerCreate')
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
            Layout::modal('passportIssuerCreate', [
                Layout::rows([
                    Input::make('issuer.fullname')
                        ->title('Название места выдачи паспорта')
                        ->placeholder('Название места выдачи паспорта')
                        ->required(),
                    Input::make('issuer.code')
                        -> title('Код места выдачи паспорта')
                        -> placeholder('Код места выдачи паспорта')
                        -> help('Необязательный к заполнению (если паспорт не РФ).')
                        -> mask([
                            'mask' => '999-999',
                        ]),
                ]),
            ])
                -> title('Добавить место выдачи паспорта')
                -> applyButton('Добавить')
                -> withoutCloseButton(),
            PassportIssuerTable::class,
        ];
    }

    public function create(PassportIssuer $issuer)
    {
        $issuer -> fill(request() -> get('issuer'))
            -> save();

        Toast::success('Место выдачи паспорта успешно добавлено.');
    }
}
