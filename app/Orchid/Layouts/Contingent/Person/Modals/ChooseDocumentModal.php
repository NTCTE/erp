<?php

namespace App\Orchid\Layouts\Contingent\Person\Modals;

use App\Models\System\Repository\DocumentSchema;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class ChooseDocumentModal extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    protected $person_id;

    public function __construct($person_id) {
        $this -> person_id = $person_id;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Select::make('doc.choose')
                -> title('Тип документа')
                -> fromQuery(DocumentSchema::where('readonly', false), 'fullname', 'id')
                -> empty('Выберите тип документа...')
                -> required(),
            Input::make('doc.person_id')
                -> type('hidden')
                -> value($this -> person_id)
        ];
    }
}
