<?php

namespace App\Orchid\Screens\Contingent\Person;

use App\Models\Org\Contingent\Document;
use App\Models\Org\Contingent\Person;
use App\Models\Org\Contingent\RelationLink;
use App\Models\System\Repository\DocumentSchema;
use App\Orchid\Layouts\Contingent\Person\CreateRows;
use App\Orchid\Layouts\Contingent\Person\DocumentsTable;
use App\Orchid\Layouts\Contingent\Person\Listeners\DocumentListener;
use App\Orchid\Layouts\Contingent\Person\Modals\AddDocumentModal;
use App\Orchid\Layouts\Contingent\Person\Modals\AddRelative;
use App\Orchid\Layouts\Contingent\Person\Modals\AddRelativeExisting;
use App\Orchid\Layouts\Contingent\Person\Modals\ChooseDocumentModal;
use App\Orchid\Layouts\Contingent\Person\Modals\EditRelative;
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

class AddEditScreen extends Screen
{
    public $person;
    public $relatives;
    public $documents;

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
                        Layout::wrapper('system.wrappers.forTabs', [
                            'entities' => [
                                Layout::rows([
                                    ModalToggle::make('Добавить новый документ')
                                        -> modal('chooseDocumentModal')
                                        -> modalTitle('Выберите тип документа')
                                        -> method('modalDocChoose')
                                        -> icon('plus')
                                        -> canSee(Auth::user() -> hasAccess('org.contingent.write'))
                                        -> class('btn btn-link rebase'),
                                    ModalToggle::make('Добавить паспорт')
                                        -> modal('addPassportModal')
                                        -> modalTitle('Добавить паспорт')
                                        -> method('modalPassportAdd')
                                        -> icon('plus')
                                        -> canSee(Auth::user() -> hasAccess('org.contingent.write'))
                                        -> class('btn btn-link rebase'),
                                ]),
                            ],
                        ]),
                        DocumentsTable::class,
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
}
