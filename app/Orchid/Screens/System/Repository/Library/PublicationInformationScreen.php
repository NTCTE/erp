<?php

namespace App\Orchid\Screens\System\Repository\Library;

use App\Models\Org\Library\Additionals\PublicationInformation;
use App\Orchid\Layouts\System\Repository\Library\PublicationInformationTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PublicationInformationScreen extends Screen
{
    public string $publicationInformation;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PublicationInformation $publicationInformations): iterable
    {
        return [
            'publicationInformations' => $publicationInformations::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Информация об издании';
    }

    public function description(): ?string
    {
        return 'Таблица с информацией об издании. Вы можете добавить новую информацию об издании, если вам не хватает какой-то.';
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
                ->modal('publicationInformationModal')
                ->method('create')
                ->icon('plus')
                ->modalTitle('Добавить информацию об издании')
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
            Layout::modal('publicationInformationModal', [
                Layout::rows([
                    Input::make('publicationInformation.fullname')
                        ->title('Название')
                        ->placeholder('Введите информацию об издании')
                        ->required(),
                    Input::make('publicationInformation.id')
                        ->type('hidden'),
                ]),
            ])
                ->withoutCloseButton()
                ->applyButton('Сохранить')
                ->staticBackdrop()
                ->async('asyncGetPublicationInformation'),
            PublicationInformationTable::class,
        ];
    }
    public function asyncGetPublicationInformation(array $fields = null): array {
        return is_null($fields) ? [] : [
            'publicationInformation' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('publicationInformation');
        if ($publicationInformation = PublicationInformation::find($get['id']))
            $publicationInformation -> update($get);
        else
            PublicationInformation::create($get);

        Toast::info('Данные сохранены');
    }
}
