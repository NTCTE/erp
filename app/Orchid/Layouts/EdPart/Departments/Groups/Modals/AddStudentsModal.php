<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Modals;

use App\Models\Org\EdPart\Departments\Group;
use App\Models\System\Relations\AdministativeDocumentsLinks;
use App\Models\System\Repository\AdministrativeDocument;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class AddStudentsModal extends Rows
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
        return [
            Matrix::make('newStudents')
                -> help('Вы можете связывать студентов с существующими Приказами о зачислении. Если вы хотите привязать не групповые Приказы о зачислении, то оставьте поле "Приказ о зачислении" пустым.')
                -> columns([
                    'Фамилия' => 'lastname',
                    'Имя' => 'firstname',
                    'Отчество' => 'patronymic',
                    'Приказ о зачислении' => 'order',
                ])
                -> fields([
                    'order' => Select::make()
                        -> fromQuery(AdministativeDocumentsLinks::where('signed_type', Group::class)
                            -> where('signed_id', request() -> route() -> parameter('group')), 'doc_name', 'administrative_document_id')
                        -> empty('Не выбрано'),
                ]),
        ];
    }
}
