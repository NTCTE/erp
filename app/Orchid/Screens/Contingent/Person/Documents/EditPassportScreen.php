<?php

namespace App\Orchid\Screens\Contingent\Person\Documents;

use App\Models\Org\Contingent\Passport;
use App\Models\Org\Contingent\Person;
use App\Orchid\Layouts\Contingent\Person\Modals\AddPassportModal;
use Illuminate\Support\Carbon;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class EditPassportScreen extends Screen
{
    public $person;
    public $passport;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Person $person, Passport $passport): iterable
    {
        return [
            'person' => $person,
            'passport' => $passport,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Редактирование паспорта';
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string {
        return $this -> person -> fullname;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                -> icon('arrow-left')
                ->route('org.contingent.person', $this -> person -> id),
            Button::make('Сохранить')
                -> icon('save')
                -> method('savePassport'),
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
            new AddPassportModal($this -> person -> id),
        ];
    }

    public function savePassport()
    {
        $passport = Passport::find(request()
            -> route()
            -> parameter('passport_id')
        );
        $data = request()
            -> input('passport');
        $data['date_of_issue'] = Carbon::createFromFormat('d.m.Y', $data['date_of_issue'])
            -> format('Y-m-d');
        $passport
            -> update($data);
        Toast::info('Паспорт успешно обновлен');
        return redirect()
            -> route('org.contingent.person', request()
            -> route()
            -> parameter('id')
        );
    }
}
