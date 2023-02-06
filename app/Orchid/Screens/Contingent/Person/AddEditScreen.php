<?php

namespace App\Orchid\Screens\Contingent\Person;

use App\Models\Org\Contingent\Person;
use App\Orchid\Layouts\Contingent\Person\CreateRows;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Str;

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
        $person -> load('attachment');

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
                        Layout::wrapper('system.wrappers.forTabs', [
                            'entities' => [
                                Layout::block([
                                    Layout::rows([
                                        Input::make('person.lastname')
                                            -> title('Фамилия')
                                            -> placeholder('Введите фамилию...')
                                            -> required()
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                        Input::make('person.firstname')
                                            -> title('Имя')
                                            -> placeholder('Введите имя...')
                                            -> required()
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                        Input::make('person.patronymic')
                                            -> title('Отчество')
                                            -> placeholder('Введите отчество...')
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                        DateTimer::make('person.birthdate')
                                            -> title('Дата рождения')
                                            -> placeholder('Введите дату рождения...')
                                            -> format('d.m.Y')
                                            -> canSee(Auth::user() -> hasAccess('org.contingent.write')),
                                        Input::make('person.birthdate')
                                            -> title('Дата рождения')
                                            -> placeholder('Введите дату рождения...')
                                            -> readonly()
                                            -> canSee(!Auth::user() -> hasAccess('org.contingent.write')),
                                        Select::make('person.sex')
                                            -> title('Пол')
                                            -> options(Person::$sexs)
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                    ]),
                                ])
                                    -> title('Персональные данные'),
                                Layout::block([
                                    Layout::rows([
                                        Input::make('person.email')
                                            -> title('Личный адрес электронной почты')
                                            -> placeholder('Введите адрес электронной почты...')
                                            -> type('email')
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                        Input::make('person.corp_email')
                                            -> title('Корпоративный адрес электронной почты')
                                            -> placeholder('Введите адрес электронной почты...')
                                            -> help('Назначается самой системой при выдаче корпоративных учетных данных.')
                                            -> type('email')
                                            -> readonly(),
                                        Input::make('person.tel')
                                            -> title('Номер телефона')
                                            -> placeholder('Введите номер телефона...')
                                            -> type('tel')
                                            -> mask([
                                                'mask' => '+7 (999) 999 99-99',
                                            ])
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                    ]),
                                ])
                                    -> title('Контактные данные'),
                                Layout::block([
                                    Layout::rows([
                                        Input::make('person.snils')
                                            -> title('СНИЛС')
                                            -> placeholder('Введите СНИЛС...')
                                            -> mask([
                                                'numericInput' => true,
                                                'mask' => '999-999-999 99',
                                            ])
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                        Input::make('person.inn')
                                            -> title('ИНН')
                                            -> placeholder('Введите ИНН...')
                                            -> mask([
                                                'numericInput' => true,
                                                'mask' => '999999999999',
                                            ])
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                        Input::make('person.hin')
                                            -> title('Полис ОМС')
                                            -> placeholder('Введите номер полиса ОМС...')
                                            -> mask([
                                                'numericInput' => true,
                                                'mask' => '9999-9999-9999-9999',
                                            ])
                                            -> readonly(!Auth::user() -> hasAccess('org.contingent.write')),
                                    ]),
                                ])
                                    -> title('Государственные данные'),
                            ]
                        ])
                    ],
                    'Родственные связи' => [
                        Layout::wrapper('system.wrappers.forTabs', [
                            'entities' => [
                                Layout::rows([

                                ]),
                            ],
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
        $input = $request -> input('person');
        $input['uuid'] = Str::uuid();
        $input['birthdate'] = Carbon::createFromFormat('d.m.Y', $input['birthdate'])
            -> format('Y-m-d');
        $person -> fill($input)
            -> save();

        Toast::success('Персона успешно сохранена');
        return redirect() -> route('org.contingent.person', $person);
    }

    public function updatePerson(Request $request, Person $person) {

    }
}
