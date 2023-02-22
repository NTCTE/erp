<?php

namespace App\Orchid\Screens\Contingent\Person;

use App\Models\Org\Contingent\Document;
use App\Models\Org\Contingent\EducationalDocument;
use App\Models\Org\Contingent\Passport;
use App\Models\Org\Contingent\Person;
use App\Models\Org\Contingent\RelationLink;
use App\Models\System\Repository\PassportIssuer;
use App\Orchid\Layouts\Contingent\Person\CreateRows;
use App\Orchid\Layouts\Contingent\Person\DocumentsTable;
use App\Orchid\Layouts\Contingent\Person\EdDocsTable;
use App\Orchid\Layouts\Contingent\Person\Modals\AddEdDoc;
use App\Orchid\Layouts\Contingent\Person\Modals\AddPassportModal;
use App\Orchid\Layouts\Contingent\Person\Modals\AddRelative;
use App\Orchid\Layouts\Contingent\Person\Modals\AddRelativeExisting;
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
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Modal;

class AddEditScreen extends Screen
{
    public $person;
    public $relatives;
    public $documents;
    public $edDocs;

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
                Layout::tabs([
                    'Персональные данные' => [
                        Layout::wrapper('system.wrappers.forTabs', [
                            'entities' => [
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
        $type -> fill([
            'person_id' => $request -> input('person_id'),
            'relative_id' => $person -> id,
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

    public function modalEducationAdd() {
        $edDoc = new EducationalDocument();
        $edDocData = request() -> input('edDoc');
        $edDocData['date_of_issue'] = Carbon::createFromFormat('d.m.Y', $edDocData['date_of_issue'])
            -> format('Y-m-d');
        $edDoc -> fill($edDocData)
            -> save();

        Toast::success('Документ об образовании успешно добавлен');
    }
}
