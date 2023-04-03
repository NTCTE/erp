<?php

namespace App\Orchid\Screens\System\Repository\Library;

use App\Models\Org\Library\Additionals\PertainingTitleInformation;
use App\Orchid\Layouts\System\Repository\Library\PertainingTitleInformationTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PertainingInformationScreen extends Screen
{

    public $pertainingInformation;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PertainingTitleInformation $pertainingTitleInformation): iterable
    {
        return [
            'pertainingTitleInformation' => $pertainingTitleInformation::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Информация, относящаяся к заглавию';
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
                ->modal('pertainingTitleInformationModal')
                ->method('create')
                ->icon('plus')
                ->modalTitle('Добавить информацию, относящуюся к заглавию.')
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
            Layout::modal('pertainingTitleInformationModal', [
                Layout::rows([
                    Input::make('pertainingTitleInformation.fullname')
                        ->title('Название')
                        ->placeholder('Введите название информации, относящейся к заглавию')
                        ->required(),
                    Input::make('pertainingTitleInformation.id')
                        ->type('hidden'),
                ])
            ])
                ->withoutCloseButton()
                ->applyButton('Сохранить')
                ->staticBackdrop()
                ->async('asyncGetPertainingInformation'),
            PertainingTitleInformationTable::class,
        ];
    }

    public function asyncGetPertainingTitleInformation(array $fields = null): array {
        return is_null($fields) ? [] : [
            'pertainingTitleInformation' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('pertainingTitleInformation');
        if ($pertainingTitleInformation = PertainingTitleInformation::find($get['id']))
            $pertainingTitleInformation -> update($get);
        else
            PertainingTitleInformation::create($get);

        Toast::info('Данные сохранены');
    }
}
