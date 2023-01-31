<?php

namespace App\Orchid\Screens\Contingent\Person;

use App\Models\Org\Contingent\Person;
use App\Orchid\Layouts\Contingent\Person\CreateRows;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AddEditScreen extends Screen
{
    public $person;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Person $person): iterable
    {
        return [
            'person' => $person,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this -> person -> exists ? 'Карточка персоны' : 'Добавить персону';
    }

    public function permission(): ?iterable
    {
        return [
            'org.contingent.read',
            'prg.contingent.write',
        ];
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
                -> method('saveNewPerson')
                -> permission('org.contingent.write')
                -> canSee(!$this -> person -> exists),
            Button::make('Обновить')
                -> icon('save')
                -> method('updatePerson')
                -> permission('org.contingent.write')
                -> canSee($this -> person -> exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        if ($this -> person -> exists) {
            return [
                Layout::tabs([
                    'Персональные данные' => [
                        Layout::rows([
                            
                        ]),
                    ],
                    'Родственные связи' => [
                        Layout::rows([
                            
                        ]),
                    ],
                    'Данные о документах' => [
                        Layout::rows([
                            
                        ]),
                    ],
                    'Работа' => [
                        Layout::rows([
                            
                        ]),
                    ],
                ]),
            ];
        } else return [
            CreateRows::class,
        ];
    }

    public function saveNewPerson(Request $request, Person $person) {
        // $request -> validate([
        //     'person.lastname' => 'required',
        // ]);

        // @ega22a: Не забудь сделать валидацию данных!

        $person -> fill($request -> input('person'))
            -> save();

        Toast::success('Персона успешно сохранена');
        return redirect() -> route('org.contingent.person', $person);
    }

    public function updatePerson(Request $request, Person $person) {

    }
}
