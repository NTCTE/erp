<?php

namespace App\Orchid\Layouts\Contingent\Person\Modals;

use App\Models\System\Repository\DocumentSchema;
use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;

class AddDocumentModal extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $doc = request() -> input('doc_id');
        return !empty($doc) ? DocumentSchema::find($doc) -> orchidSchema() : [];
    }
}
