<?php

namespace App\Orchid\Screens\System\Repository;

use App\Models\System\Repository\Language;
use App\Orchid\Layouts\System\Repository\LanguageTable;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class LanguagesScreen extends Screen
{
    public string $languages;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Language $languages): iterable
    {
        return [
            'language' => $languages::paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Языки';
    }

    /**
     * @return string|null
     */

    public function description(): ?string
    {
        return 'Таблица с самыми распространёнными языки. Вы можете добавить новый язык, если вам не хватает какого-то.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить новый язык')
                -> icon('plus')
                -> route('system.repository.language.edit'),
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
            LanguageTable::class,
        ];
    }
}
