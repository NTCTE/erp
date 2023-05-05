<?php

namespace App\Orchid\Screens\System\Repository\Library;

use App\Models\Org\Library\Additionals\Author;
use App\Orchid\Layouts\System\Repository\Library\AuthorTable;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AuthorScreen extends Screen
{
    public $authors;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Author $author): iterable
    {
        return [
            'authors' => $author::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Авторы';
    }

    public function description():? string {
        return 'Здесь вы можете добавить автора.';
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
                    ->modal('authorModal')
                    ->method('create')
                    ->icon('plus')
                    ->modalTitle('Добавить автора.')
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
            Layout::modal('authorModal', [
                Layout::rows([
                    Input::make('author.lastname')
                        ->title('Фамилия')
                        ->placeholder('Введите фамилию автора')
                        ->required(),
                    Input::make('author.firstname')
                        ->title('Имя')
                        ->placeholder('Введите имя автора')
                        ->required(),
                    Input::make('author.patronymic')
                        ->title('Отчество')
                        ->placeholder('Введите отчество автора')
                        ->required(),
                    Input::make('author.id')
                        ->type('hidden'),
                ])
            ])
                ->withoutCloseButton()
                ->applyButton('Сохранить')
                ->staticBackdrop()
                ->async('asyncGetAuthor'),
            AuthorTable::class,
        ];
    }

    public function asyncGetAuthor(array $fields = null): array {
        return is_null($fields) ? [] : [
            'author' => $fields,
        ];
    }

    public function create() {
        $get = request() -> get('author');
        if ($author = Author::find($get['id']))
            $author -> update($get);
        else
            Author::create($get);

        Toast::info('Данные сохранены');
    }
}
