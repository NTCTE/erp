<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Modals;

use App\Models\System\Repository\AdministrativeDocument;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class MoveToAcademicLeaveModal extends Rows
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
            Relation::make('student.order_id')
                -> required()
                -> title('Приказ о переводе')
                -> allowEmpty()
                -> placeholder('Выберите Приказ о переводе...')
                -> fromModel(AdministrativeDocument::class, 'fullname', 'id')
                -> searchColumns('fullname', 'number', 'date_at')
                -> displayAppend('formatted')
                -> help('Вы можете выбрать только имеющийся Приказ о переводе, если вы хотите создать новый Приказ о переводе, то вам нужно сделать это через репозиторий административных документов.'),
            TextArea::make('student.reason')
                -> title('Причина перевода')
                -> rows(5)
                -> placeholder('Введите причину перевода...')
                -> required(),
            DateTimer::make('student.expires')
                -> title('Дата окончания академического отпуска')
                -> placeholder('Выберите дату...')
                -> required()
                -> format('d.m.Y'),
            TextArea::make('student.additionals')
                -> title('Дополнительные сведения')
                -> rows(5)
                -> placeholder('Введите дополнительные сведения...')
                -> help('Эти сведения будут отражены в журнале движения студента.'),
            Input::make('student.id')
                -> type('hidden'),
        ];
    }
}
