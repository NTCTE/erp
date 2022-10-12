<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Воркспейс';
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        $time = Date('G');
        $ret = '';
        if ($time >= 0 && $time < 6)
            $ret = '🌃 Доброй ночи!';
        elseif ($time >= 6 && $time < 12)
            $ret = '🌅 Доброе утро!';
        elseif ($time >= 12 && $time < 18)
            $ret = '🏙️ Добрый день!';
        else 
            $ret = '🌇 Добрый вечер!';
        return "{$ret} Мы рады вас видеть в системе.";
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Сайт колледжа')
                ->href(config('nttek.contact.site'))
                ->icon('globe-alt'),

            Link::make('Документация')
                ->href('#')
                ->icon('docs'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('nttek.welcome'),
        ];
    }
}
