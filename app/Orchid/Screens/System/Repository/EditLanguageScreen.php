<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\Language;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class EditLanguageScreen extends Screen
{

    public $language;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Language $language): iterable
    {
        return [
            'language' => $language,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->language->exist ? 'Редактировать язык': 'Добавить новый язык';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
            return [
                Button::make('Сохранить')
                    -> icon('save')
                    -> confirm('Вы уверены, что хотите сохранить новый язык?')
                    -> method('saveLanguage')
                    ->canSee(!$this->language->exists),

                Button::make('Обновить')
                    ->icon('note')
                    ->method('saveLanguage')
                    ->canSee($this->language->exists),
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
            Layout::rows([
                Input::make('language.fullname')
                    -> title('Название языка')
                    -> placeholder('Введите название...')
                    -> horizontal()
                    -> required(),
            ])
        ];
    }
    public function saveLanguage(Request $request, Language $language) {
        $language -> fill($request -> input('language'))
            -> save();

        Toast::success('Данные о языке успешно сохранены!');

        return redirect()
            -> route('system.repository.languages');
    }
}

