<?php

namespace App\Orchid\Screens\Contingent\Person;

use App\Models\Org\Contingent\Document;
use App\Models\Org\Contingent\EducationalDocument;
use App\Models\Org\Contingent\Passport;
use App\Models\Org\Contingent\Person;
use App\Models\Org\Contingent\RelationLink;
use App\Models\System\Repository\Position;
use App\Models\System\Repository\Workplace;
use App\Orchid\Layouts\Contingent\Person\DocumentsTable;
use App\Orchid\Layouts\Contingent\Person\EdDocsTable;
use App\Orchid\Layouts\Contingent\Person\Modals\AddEdDoc;
use App\Orchid\Layouts\Contingent\Person\Modals\AddPassportModal;
use App\Orchid\Layouts\Contingent\Person\Modals\AddRelative;
use App\Orchid\Layouts\Contingent\Person\Modals\AddRelativeExisting;
use App\Orchid\Layouts\Contingent\Person\Modals\AddWorkplaceModal;
use App\Orchid\Layouts\Contingent\Person\Modals\ChooseDocumentModal;
use App\Orchid\Layouts\Contingent\Person\Modals\EditRelative;
use App\Orchid\Layouts\Contingent\Person\PassportsTable;
use App\Orchid\Layouts\Contingent\Person\Personal\Contacts;
use App\Orchid\Layouts\Contingent\Person\Personal\Government;
use App\Orchid\Layouts\Contingent\Person\Personal\Personal;
use App\Orchid\Layouts\Contingent\Person\RelativesTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Sight;

class AddEditScreen extends Screen
{
    public $person;
    public $relatives;
    public $documents;
    public $edDocs;
    public $workplace;
    public $position;

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
            'relatives' => $person -> relatives() -> paginate(),
            'documents' => $person -> documents() -> paginate(),
            'passports' => $person -> passports() -> paginate(),
            'edDocs' => $person -> edDocs() -> paginate(),
            'workplace' => $person -> workplace,
            'position' => $person -> position,
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
                    new AddRelativeExisting($this -> person -> id),
                ])
                    -> withoutCloseButton()
                    -> applyButton('Связать'),
                Layout::modal('addRelativeModal', [
                    new AddRelative($this -> person -> id),
                ])
                    -> size(Modal::SIZE_LG)
                    -> withoutCloseButton()
                    -> applyButton('Добавить'),
                Layout::modal('editRelationModal', [
                    EditRelative::class,
                ])
                    -> title('Редактировать родственную связь')
                    -> withoutCloseButton()
                    -> applyButton('Обновить'),
                Layout::modal('chooseDocumentModal', [
                   new ChooseDocumentModal($this -> person -> id),
                ])
                    -> withoutCloseButton()
                    -> applyButton('Далее'),

                Layout::modal('addPassportModal', [
                    new AddPassportModal($this -> person -> id),
                ])
                    -> withoutCloseButton()
                    -> applyButton('Добавить'),
                Layout::modal('addEdDocumentModal', [
                    new AddEdDoc($this -> person -> id),
                ])
                    -> withoutCloseButton()
                    -> applyButton('Добавить'),
                Layout::modal('addWorkplaceModal', [
                    new AddWorkplaceModal($this -> person -> id),
                ])
                    -> withoutCloseButton()
                    -> applyButton('Изменить'),
                Layout::tabs([
                    'Персональные данные' => [
                        Layout::wrapper('system.wrappers.forTabs', [
                            'entities' => [
                                Layout::rows([
                                    Group::make([
                                        Button::make('Сохранить')
                                            -> icon('save')
                                            -> class('btn rebase')
                                            -> method('savePerson'),
                                        Button::make('Удалить')
                                            -> icon('trash')
                                            -> confirm('Вы уверены, что хотите удалить персону?')
                                            -> method('deletePerson')
                                            -> class('btn rebase'),
                                    ])
                                        -> autoWidth(),
                                ]),
                                Layout::block([
                                    Personal::class,
                                ])
                                    -> title('Персональные данные'),
                                Layout::block([
                                    Contacts::class,
                                ])
                                    -> title('Контактные данные'),
                                Layout::block([
                                    Government::class,
                                ])
                                    -> title('Государственные данные'),
                            ]
                        ])
                    ],
                    'Родственные связи' => [
                        Layout::wrapper('system.wrappers.forTabs', [
                            'entities' => [
                                Layout::rows([
                                    Group::make([
                                        ModalToggle::make('Добавить новую родственную связь')
                                            -> modal('addRelativeModal')
                                            -> modalTitle('Добавить новую родственную связь')
                                            -> method('modalRelAdd')
                                            -> icon('plus')
                                            -> class('btn btn-link rebase'),
                                        ModalToggle::make('Добавить связь с имеющейся персоной')
                                            -> modal('addRelativeExistingModal')
                                            -> modalTitle('Добавить связь с имеющейся персоной')
                                            -> method('modalRelAddExisting')
                                            -> icon('plus')
                                            -> class('btn btn-link rebase'),
                                    ])
                                        -> autoWidth(),
                                ]),
                            ],
                        ])
                            -> canSee(Auth::user() -> hasAccess('org.contingent.write')),
                        RelativesTable::class,
                    ],
                    'Данные о документах' => [
                        Layout::wrapper('system.wrappers.forTabs', [
                            'entities' => [
                                Layout::rows([
                                    Group::make([
                                        ModalToggle::make('Добавить новый документ')
                                            -> modal('chooseDocumentModal')
                                            -> modalTitle('Выберите тип документа')
                                            -> method('modalDocChoose')
                                            -> icon('plus')
                                            -> class('btn btn-link rebase'),
                                        ModalToggle::make('Добавить паспорт')
                                            -> modal('addPassportModal')
                                            -> modalTitle('Добавить паспорт')
                                            -> method('modalPassportAdd')
                                            -> icon('plus')
                                            -> class('btn btn-link rebase'),
                                        ModalToggle::make('Добавить документ об образовании')
                                            -> modal('addEdDocumentModal')
                                            -> modalTitle('Добавить документ об образовании')
                                            -> method('modalEducationAdd')
                                            -> icon('plus')
                                            -> class('btn btn-link rebase'),
                                    ])
                                        -> autoWidth(),
                                ]),
                            ],
                        ])
                            -> canSee(Auth::user() -> hasAccess('org.contingent.write')),
                        DocumentsTable::class,
                        PassportsTable::class,
                        EdDocsTable::class,
                    ],
                    'Работа' => [
                        Layout::wrapper('system.wrappers.forTabs', [
                            'entities' => [
                                Layout::rows([
                                    Group::make([
                                        ModalToggle::make('Изменить место работы')
                                            -> modal('addWorkplaceModal')
                                            -> modalTitle('Изменить место работы')
                                            -> method('modalWorkplaceAdd')
                                            -> icon('plus')
                                            -> class('btn btn-link rebase'),
                                    ]),
                                ]),
                            ],
                        ]),
                        Layout::legend('dummy_target', [
                            Sight::make('workplace.fullname', 'Место работы')
                                -> render(function () {
                                    return !is_null($this -> workplace) ? $this -> workplace -> fullname : 'Не указано';
                                }),
                            Sight::make('position', 'Должность')
                                -> render(function() {
                                    return !is_null($this -> position) ? $this -> position -> fullname : 'Не указано';
                                }),
                            Sight::make('workplace.tel', 'Рабочий номер телефона')
                                -> render(function () {
                                    return !is_null($this -> workplace) ? $this -> workplace -> tel : 'Не указано';
                                }),
                            Sight::make('workplace.email', 'Рабочий адрес электронной почты')
                                -> render(function () {
                                    return !is_null($this -> workplace) ? $this -> workplace -> email : 'Не указано';
                                }),
                        ]),
                    ],
                ]),
            ];
        } else return [
            Layout::block([
                Personal::class,
            ])
                -> title('Персональные данные'),
            Layout::block([
                Contacts::class,
            ])
                -> title('Контактные данные'),
            Layout::block([
                Government::class,
            ])
                -> title('Государственные данные'),
        ];
    }

    public function saveNewPerson(Request $request, Person $person) {

        $request -> validate([

            'person.lastname' => 'required|string|max:255',
            'person.firstname' => 'required|string|max:255',
            'person.patronymic' => 'nullable|string|max:255',
            'person.email' => 'nullable|email|max:255',
            'person.corp_email' => 'nullable|email|max:255',
            'person.tel' => 'nullable|string|max:20',
            'person.snils' => 'nullable|string|max:20',
            'person.inn' => 'nullable|string|max:20',
            'person.hin' => 'nullable|string|max:20',
            'person.sex' => 'nullable|in:1,2',
            'person.birthdate' => 'nullable|date_format:d.m.Y',
        ], [
            'person.lastname.required' => 'Поле "Фамилия" является обязательным для заполнения',
            'person.firstname.required' => 'Поле "Имя" является обязательным для заполнения',
            'person.patronymic.max' => 'Поле "Отчество" не может быть длиннее 255 символов',
            'person.email.email' => 'Поле "Электронная почта" должно быть действительным адресом электронной почты',
            'person.email.max' => 'Поле "Электронная почта" не может быть длиннее 255 символов',
            'person.corp_email.email' => 'Поле "Корпоративная электронная почта" должно быть действительным адресом электронной почты',
            'person.corp_email.max' => 'Поле "Корпоративная электронная почта" не может быть длиннее 255 символов',
            'person.tel.max' => 'Поле "Телефон" не может быть длиннее 20 символов',
            'person.snils.max' => 'Поле "СНИЛС" не может быть длиннее 11 символов',
            'person.inn.max' => 'Поле "ИНН" не может быть длиннее 12 символов',
            'person.hin.max' => 'Поле "Номер медицинского полиса" не может быть длиннее 16 символов',
            'person.sex.in' => 'Поле "Пол" должно быть заполнено значением 1 (мужчина) или 2 (женщина)',
            'person.birthdate.date_format' => 'Поле "Дата рождения" должно быть в формате "дд.мм.гггг"',
            ]);

        $input = $request -> input('person');
        $input['birthdate'] = Carbon::createFromFormat('d.m.Y', $input['birthdate'])
            -> format('Y-m-d');
        $person -> fill($input)
            -> save();

        Toast::success('Персона успешно сохранена');
        return redirect() -> route('org.contingent.person', $person);
    }

    public function savePerson(Request $request) {

        $request -> validate([

            'person.lastname' => 'required|string|max:100',
            'person.firstname' => 'required|string|max:100',
            'person.patronymic' => 'nullable|string|max:100',
            'person.email' => 'nullable|email|max:200',
            'person.corp_email' => 'nullable|email|max:200',
            'person.tel' => 'nullable|string|max:20',
            'person.snils' => 'nullable|string|max:20',
            'person.inn' => 'nullable|string|max:20',
            'person.hin' => 'nullable|string|max:20',
            'person.sex' => 'nullable|in:1,2',
            'person.birthdate' => 'nullable|date_format:d.m.Y',
        ],[
            'person.lastname.required' => 'Поле "Фамилия" является обязательным для заполнения',
            'person.firstname.required' => 'Поле "Имя" является обязательным для заполнения',
            'person.patronymic.max' => 'Поле "Отчество" не может быть длиннее 255 символов',
            'person.email.email' => 'Поле "Электронная почта" должно быть действительным адресом электронной почты',
            'person.email.max' => 'Поле "Электронная почта" не может быть длиннее 255 символов',
            'person.corp_email.email' => 'Поле "Корпоративная электронная почта" должно быть действительным адресом электронной почты',
            'person.corp_email.max' => 'Поле "Корпоративная электронная почта" не может быть длиннее 255 символов',
            'person.tel.max' => 'Поле "Телефон" не может быть длиннее 20 символов',
            'person.snils.max' => 'Поле "СНИЛС" не может быть длиннее 11 символов',
            'person.inn.max' => 'Поле "ИНН" не может быть длиннее 12 символов',
            'person.hin.max' => 'Поле "Номер медицинского полиса" не может быть длиннее 16 символов',
            'person.sex.in' => 'Поле "Пол" должно быть заполнено значением 1 (мужчина) или 2 (женщина)',
            'person.birthdate.date_format' => 'Поле "Дата рождения" должно быть в формате "дд.мм.гггг"',
        ]);

        $person = request() -> input('person');
        $person['birthdate'] = !empty($person['birthdate']) && $person['birthdate'] != 'Не указана' ? Carbon::createFromFormat('d.m.Y', $person['birthdate'])
            -> format('Y-m-d') : null;
        if ($person['corp_email'] == 'Не выдан')
            unset($person['corp_email']);
        Person::find(request() -> route() -> parameter('id'))
            -> fill($person)
            -> save();

        Toast::success('Персона успешно сохранена');
    }

    public function deletePerson() {
        Person::find(request() -> route() -> parameter('id'))
            -> delete();

        Toast::success('Персона успешно удалена');
        return redirect()
            -> route('org.contingent');
    }

    public function updatePerson(Request $request, Person $person) {

    }

    public function modalRelAdd(Request $request) {
        $type = new RelationLink();
        $person = new Person();
        $request->validate([
            'relative.lastname' => 'required|string|max:100',
            'relative.firstname' => 'required|string|max:100',
            'relative.patronymic' => 'nullable|string|max:100',
            'rel_type' => 'required|exists:relation_types,id',
        ], [
            'relative.lastname.required' => 'Поле "Фамилия" является обязательным для заполнения',
            'relative.firstname.required' => 'Поле "Имя" является обязательным для заполнения',
            'rel_type.required' => 'Поле "Тип родственной связи" является обязательным для заполнения',
            'rel_type.exists' => 'Выбранный тип родственной связи не существует',
        ]);


        $input = $request -> input('relative');
        $person -> fill($input)
            -> save();
        $type -> fill([
            'person_id' => $request -> input('person_id'),
            'relative_id' => $person -> id,
            'relation_type_id' => $request -> input('rel_type'),
        ])
            -> save();
    }

    public function modalRelAddExisting(Request $request) {

        $type = new RelationLink();

        $request->validate([
            'relative.relative_id' => 'required|exists:relation_types,id',
            'relative.person_id' => 'required|exists:persons,id',
        ], [
            'relative.relative_id.required' => 'Поле "Тип родственной связи" является обязательным для заполнения',
            'relative.relative_id.exists' => 'Выбранный тип родственной связи не существует',
            'relative.person_id.required' => 'Не указан идентификатор родственника',
            'relative.person_id.exists' => 'Родственник не найден',
        ]);

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

    public function modalDocChoose() {
        $get = request() -> input('doc');
        return redirect()
            -> route('org.contingent.person.document', [
                'id' => $get['person_id'],
                'type' => $get['choose'],
            ]);
    }

    public function removeDoc() {
        $doc = Document::find(request() -> input('doc_id'));
        $doc -> delete();

        Toast::success('Документ успешно удален');
    }

    public function modalPassportAdd(Request $request) {
        $passport = new Passport();

        $request->validate([
            'passport.series' => 'nullable|string|max:10',
            'passport.number' => 'required|string|max:20',
            'passport.passport_issuer_id' => 'required|sometimes|string|numeric',
            'passport.date_of_issue' => 'required|date_format:d.m.Y',
            'passport.birthplace' => 'nullable|string',
        ], [
            'passport.series.max' => 'Максимум 10 символов',
            'passport.number.required' => 'Поле "Номер" должно быть заполнено',
            'passport.number.max' => 'Поле "Номер" не должно превышать 20 символов',
            'passport.passport_issuer_id.required' => 'Поле "Кем выдан" должно быть заполнено',
            'passport.date_of_issue.required' => 'Поле "дата выдачи паспорта" должно быть заполнено'

        ]);

        $passportData = $request -> input('passport');
        $passport -> where('person_id', '=', $passportData['person_id'])
            -> update([
                'is_main' => false,
            ]);
        $passportData['date_of_issue'] = Carbon::createFromFormat('d.m.Y', $passportData['date_of_issue'])
            -> format('Y-m-d');
        $passport -> fill($passportData)
            -> save();

        Toast::success('Паспорт успешно добавлен');
    }

    public function makeMainPassport() {
        Passport::where('person_id', '=', request() -> route() -> parameter('id'))
            -> update([
                'is_main' => false,
            ]);
        Passport::where('id', '=', request() -> input('passport_id'))
            -> update([
                'is_main' => true,
            ]);

        Toast::success('Паспорт успешно сделан основным');
    }

    public function removePassport() {
        Passport::where('id', '=', request() -> input('passport_id'))
            -> delete();

        Toast::success('Паспорт успешно удален');
    }

    public function editPassport() {
        return redirect() -> route('org.contingent.person.passport', [
            'id' => request() -> route() -> parameter('id'),
            'passport_id' => request() -> input('passport_id'),
        ]);
    }

    public function modalEducationAdd(Request $request) {

        $request->validate([
            'edDoc.series' => 'nullable|string|max:10',
            'edDoc.number' => 'required|string|max:25',
            'edDoc.date_of_issue' => 'required|date_format:d.m.Y',
            'edDoc.educational_doc_issuer_id' => 'required|exists:educational_doc_issuers,id',
            'edDoc.educational_doc_type_id' => 'required|exists:educational_doc_types,id',
            'edDoc.average_mark' => 'required|numeric|min:3|max:5'

        ], [
            'edDoc.series.required' => 'Поле "Серия документа" обязательно для заполнения',
            'edDoc.series.max' => 'Поле "Серия документа" не должно превышать 10 символов',
            'edDoc.number.required' => 'Поле "Номер документа" обязательно для заполнения',
            'edDoc.number.max' => 'Поле "Номер документа" не должно превышать 25 символов',
            'edDoc.date_of_issue.required' => 'Поле "Дата выдачи" обязательно для заполнения',
            'edDoc.date_of_issue.date_format' => 'Неверный формат даты. Ожидается формат "дд.мм.гггг"',
            'edDoc.educational_doc_issuer_id.required' => 'Поле "Кем выдан" обязательно для заполнения',
            'edDoc.educational_doc_type_id.required' => 'Поле "Тип документа" обязательно для заполнения',
            'edDoc.average_mark.required' => 'После "Средний балл" обязательно для заполнения',
            'edDoc.average_mark.min' => 'Средний балл не может быть больше 3',
            'edDoc.average_mark.max' => 'Средний балл не может быть больше 5'
        ]);

        $edDoc = new EducationalDocument();
        $edDocData = request() -> input('edDoc');
        $edDocData['date_of_issue'] = Carbon::createFromFormat('d.m.Y', $edDocData['date_of_issue'])
            -> format('Y-m-d');
        $edDoc -> fill($edDocData)
            -> save();

        Toast::success('Документ об образовании успешно добавлен');
    }

    public function makeMainEdDoc() {



        EducationalDocument::where('person_id', '=', request() -> route() -> parameter('id'))
            -> update([
                'is_main' => false,
            ]);
        EducationalDocument::where('id', '=', request() -> input('edDoc_id'))
            -> update([
                'is_main' => true,
            ]);

        Toast::success('Документ об образовании успешно сделан основным');
    }

    public function removeEdDoc() {
        EducationalDocument::where('id', '=', request() -> input('edDoc_id'))
            -> delete();

        Toast::success('Документ об образовании успешно удален');
    }

    public function modalWorkplaceAdd(Request $request) {

        $request->validate([
            'workplaces.fullname' => 'required|max:255|exists:workplace,id',
            'workplaces.tel' => 'nullable|string|max:20',
            'workplaces.email' => 'nullable|string|email|max:150',
            'positions.fullname' => 'required|max:200|exists:position,id',
        ], [
            'workplaces.fullname.required' => 'Поле "Место работы" обязательно для заполнения',
            'workplaces.fullname.max' => 'Поле "Полное наименование места работы" не должно превышать 255 символов',
            'workplaces.tel.max' => 'Поле "Телефон" не должно превышать 20 символов',
            'workplaces.email.max' => 'Поле "E-mail" не должно превышать 150 символов',
            'workplaces.email.email' => 'Поле "E-mail" должно быть валидным email-адресом',
            'positions.fullname.required' => 'Поле "Должность" обязательно для заполнения',
            'positions.fullname.max' => 'Поле "Должность" не должно превышать 255 символов',
        ]);

        $workplace = Workplace::firstWhere('fullname', request() -> input('workplace')['fullname']);
        $raw = request() -> input('workplace');
        if (is_null($raw['tel']))
            unset($raw['tel']);
        if (is_null($raw['email']))
            unset($raw['email']);
        $workplace
            -> fill($raw)
            -> save();

        $position = Position::firstWhere('fullname', request() -> input('position')['fullname']);
        $position
            -> fill(request() -> input('position'))
            -> save();

        Person::find(request() -> route() -> parameter('id'))
            -> fill([
                'workplace_id' => $workplace -> id,
                'position_id' => $position -> id,
            ])
            -> save();

        Toast::success('Место работы успешно обновлено');
    }

}
