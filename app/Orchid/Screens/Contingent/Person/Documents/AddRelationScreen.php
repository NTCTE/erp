<?php

namespace App\Orchid\Screens\Contingent\Person\Documents;

use App\Models\Org\Contingent\Document;
use App\Models\Org\Contingent\Person;
use App\Models\System\Repository\DocumentSchema;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class AddRelationScreen extends Screen
{
    public $person;
    public $schema;
    public $document;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Person $person, DocumentSchema $schema, Document $document): iterable
    {
        return [
            'person' => $person -> find(request() -> route()
                -> parameter('id')),
            'schema' => $schema -> find(request() -> route()
                -> parameter('type')),
            'document' => $document -> find(request() -> route()
                -> parameter('doc_id')),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this -> schema -> fullname;
    }

    /**
     * The description of the screen displayed in the header.
     *
     * @return string|null
     */
    public function description(): ?string
    {
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
                -> route('org.contingent.person', $this -> person -> id),
            Button::make('Сохранить')
                -> icon('save')
                -> method('saveDoc'),
        ];
    }

    public function permission(): ?array
    {
        return [
            'org.contingent.write',
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
            Layout::rows(
                $this -> schema -> orchidSchema($this -> document -> document),
            ),
        ];
    }

    public function saveDoc(Document $document)
    {
        $input = request() -> get('doc');
        $input['person_id'] = request()
            -> route()
            -> parameter('id');

        $document -> fill($input)
            -> save();

        Toast::success('Документ добавлен');
        return redirect()
            -> route('org.contingent.person', $input['person_id']);
    }
}
