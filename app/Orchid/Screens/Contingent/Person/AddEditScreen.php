<?php

namespace App\Orchid\Screens\Contingent\Person;

use App\Models\Org\Contingent\Person;
use App\Models\Org\Contingent\RelationLink;
use App\Models\System\Repository\RelationType;
use App\Orchid\Layouts\Contingent\Person\CreateRows;
use App\Orchid\Layouts\Contingent\Person\RelativesTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Modal;

class AddEditScreen extends Screen
{
    public $person;
    public $documentsSchemas;

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
            'documentsSchemas' => $person -> documentsSchemas,
            'relatives' => $person -> relatives,
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
                Layout::modal('addRelativeExistingModal', [
                    Layout::rows([
                        Relation::make('relative.relative_id')
                            -> title('Персона')
                            -> fromModel(Person::class, 'lastname', 'id')
                            -> searchColumns('firstname', 'patronymic')
                            -> displayAppend('fullname')
                            -> empty('Выберите персону...')
                            -> required(),
                        Select::make('relative.relation_type_id')
                            -> title('Тип родства')
                            -> class('form-control rebase')
                            -> fromModel(RelationType::class, 'fullname')
                            -> empty('Выберите тип родства...'),
                        Input::make('relative.person_id')
                            -> type('hidden')
                            -> value($this -> person -> id),
                    ]),
                ])
                    -> withoutCloseButton()
                    -> applyButton('Связать'),
                Layout::modal('addRelativeModal', [
                    Layout::rows([
                        Input::make('relative.lastname')
                            -> class('form-control rebase')
                            -> title('Фамилия')
                            -> placeholder('Введите фамилию...')
                            -> required(),
                        Input::make('relative.firstname')
                            -> class('form-control rebase')
                            -> title('Имя')
                            -> placeholder('Введите имя...')
                            -> required(),
                        Input::make('relative.patronymic')
                            -> class('form-control rebase')
                            -> title('Отчество')
                            -> placeholder('Введите отчество...'),
                        Select::make('rel_type')
                            -> title('Тип родства')
                            -> class('form-control rebase')
                            -> fromModel(RelationType::class, 'fullname')
                            -> empty('Выберите тип родства...'),
                        Input::make('person_id')
                            -> type('hidden')
                            -> value($this -> person -> id),
                    ]),
                ])
                    -> size(Modal::SIZE_LG)
                    -> withoutCloseButton()
                    -> applyButton('Добавить'),
                Layout::modal('editRelationModal', [
                    Layout::rows([
                        Select::make('rel_type')
                            -> title('Тип родства')
                            -> class('form-control rebase')
                            -> fromModel(RelationType::class, 'fullname')
                            -> empty('Выберите тип родства...')
                            -> required(),
                    ]),
                ])
                    -> title('Редактировать родственную связь')
                    -> withoutCloseButton()
                    -> applyButton('Обновить'),
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
                                    ModalToggle::make('Добавить новую родственную связь')
                                        -> modal('addRelativeModal')
                                        -> modalTitle('Добавить новую родственную связь')
                                        -> method('modalRelAdd')
                                        -> icon('plus')
                                        -> canSee(Auth::user() -> hasAccess('org.contingent.write'))
                                        -> class('btn btn-link rebase'),
                                    ModalToggle::make('Добавить связь с имеющейся персоной')
                                        -> modal('addRelativeExistingModal')
                                        -> modalTitle('Добавить связь с имеющейся персоной')
                                        -> method('modalRelAddExisting')
                                        -> icon('plus')
                                        -> canSee(Auth::user() -> hasAccess('org.contingent.write'))
                                        -> class('btn btn-link rebase'),
                                ]),
                            ],
                        ]),
                        RelativesTable::class,
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

    public function modalRelAdd(Request $request) {
        $type = new RelationLink();
        $person = new Person();
        // $request -> validate([

        // ]);
        // @ega22a: Не забудь сделать валидацию данных!

        $input = $request -> input('relative');
        $input['uuid'] = Str::uuid();
        $person -> fill($input)
            -> save();
        $person_id = $person -> id;
        $type -> fill([
            'person_id' => $request -> input('person_id'),
            'relative_id' => $person_id,
            'relation_type_id' => $request -> input('rel_type'),
        ])
            -> save();
    }

    public function modalRelAddExisting(Request $request) {
        $type = new RelationLink();

        $type -> fill($request -> input('relative'))
            -> save();

        Toast::success('Родственная связь успешно добавлена');
    }

    public function editRelation() {
        $link = RelationLink::find(request() -> input('rel_id'));
        $link -> fill([
            'relation_type_id' => request() -> input('rel_type'),
        ])
            -> save();

        Toast::success('Родственная связь успешно изменена');
    }

    public function deleteRelation() {
        $link = RelationLink::find(request() -> input('rel_id'));
        $link -> delete();
        if (request() -> input('purge')) {
            $person = Person::find($link -> relative_id);
            $person -> delete();
        }

        Toast::success('Родственная связь успешно удалена');
    }
}
