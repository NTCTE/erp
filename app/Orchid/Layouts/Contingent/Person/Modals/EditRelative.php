<?php

namespace App\Orchid\Layouts\Contingent\Person\Modals;

use App\Models\System\Repository\RelationType;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class EditRelative extends Rows
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
            Select::make('rel_type')
                -> title('Тип родства')
                -> class('form-control rebase')
                -> fromModel(RelationType::class, 'fullname')
                -> empty('Выберите тип родства...')
                -> required(),
        ];
    }
}
