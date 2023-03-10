<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Modals;

use App\Models\System\Repository\AdministrativeDocument;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class EditAdditionalInfoModal extends Rows
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
            TextArea::make('student.additionals')
                -> title('Дополнительные сведения')
                -> rows(5)
                -> placeholder('Введите дополнительные сведения...'),
            Relation::make('student.enrollment_order_id')
                -> title('Приказ о зачислении')
                -> allowEmpty()
                -> fromModel(AdministrativeDocument::class, 'fullname', 'id')
                -> searchColumns('fullname', 'number', 'date_at')
                -> displayAppend('formatted')
                -> help('Вы можете выбрать только имеющийся Приказ о зачислении, если вы хотите создать новый Приказ о зачислении, то вам нужно сделать это через репозиторий административных документов.'),
            CheckBox::make('student.budget')
                -> title('Инофрмация об оплате за обучение')
                -> placeholder('Бюджетная форма обучения')
                -> sendTrueOrFalse(),
            Input::make('student.id')
                -> type('hidden'),
        ];
    }
}
