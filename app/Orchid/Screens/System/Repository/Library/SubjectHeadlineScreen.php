<?php

namespace App\Orchid\Screens\System\Repository\Library;

use App\Models\Org\Library\Additionals\SubjectHeadline;
use App\Orchid\Layouts\System\Repository\Library\SubjectHeadlineTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SubjectHeadlineScreen extends Screen
{

    public $subjectHeadline;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(SubjectHeadline $subjectHeadlines): iterable
    {
        return [
            'subjectHeadlines' => $subjectHeadlines::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Предметные заголовки';
    }

    public function description():? string {
        return 'Здесь вы можете добавить предметный заголовок.';
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
                ->modal('subjectHeadlineModal')
                ->method('create')
                ->icon('plus')
                ->modalTitle('Добавить предметный заголовок')
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
            Layout::modal('subjectHeadlineModal', [
                Layout::rows([
                    Input::make('subjectHeadline.fullname')
                        ->title('Название')
                        ->placeholder('Введите название предметного заголовка')
                        ->required(),
                    Input::make('subjectHeadline.id')
                        ->type('hidden'),
                ])
            ])
                ->withoutCloseButton()
                ->applyButton('Сохранить')
                ->staticBackdrop()
                ->async('asyncGetSubjectHeadline'),
            SubjectHeadlineTable::class,
        ];
    }

    public function asyncGetSubjectHeadline(array $fields = null): array {
        return is_null($fields) ? [] : [
            'subjectHeadline' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('subjectHeadline');
        if ($subjectHeadline = SubjectHeadline::find($get['id']))
            $subjectHeadline -> update($get);
        else
            SubjectHeadline::create($get);

        Toast::info('Данные сохранены');
    }
}
