<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Students\Listeners;

use App\Models\System\Repository\AdministrativeDocument;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout as FacadesLayout;

class OrderListener extends Listener
{
    /**
     * List of field names for which values will be joined with targets' upon trigger.
     *
     * @var string[]
     */
    protected $extraVars = [];

    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'async.existing_order_cb',
    ];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncUpdateAppendGroup';

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        return [
            FacadesLayout::rows([
                CheckBox::make('async.existing_order_cb')
                    -> sendTrueOrFalse()
                    -> title('Связь')
                    -> placeholder('Связать с существующим Приказом')
                    -> help('Эти данные устанавливаются единожды, редактирование связи с каноничным Приказом невозможно, возможно только редактирование данных Приказа через репозиторий административных документов.')
                    -> horizontal(),
                Input::make('order.number')
                    -> required()
                    -> title('Номер')
                    -> placeholder('Введите номер Приказа...')
                    -> horizontal()
                    -> canSee($this -> showInputs()),
                DateTimer::make('order.date_at')
                    -> required()
                    -> title('Дата Приказа')
                    -> placeholder('Введите дату Приказа...')
                    -> format('d.m.Y')
                    -> horizontal()
                    -> canSee($this -> showInputs()),
                TextArea::make('order.fullname')
                    -> required()
                    -> rows(5)
                    -> title('Полное наименование Приказа')
                    -> placeholder('Введите полное наименование Приказа...')
                    -> horizontal()
                    -> canSee($this -> showInputs()),
                Relation::make('order.administrative_document_id')
                    -> required()
                    -> title('Существующий Приказ')
                    -> placeholder('Выберите Приказ...')
                    -> fromModel(AdministrativeDocument::class, 'fullname', 'id')
                    -> searchColumns('fullname', 'number', 'date_at')
                    -> displayAppend('formatted')
                    -> horizontal()
                    -> canSee(!$this -> showInputs()),
            ]),
        ];
    }

    private function showInputs(): bool {
        return (
            !$this
                -> query
                -> has('async.existing_order_cb')
            &&
            !$this
                -> query
                -> get('async.existing_order_cb')
        ) || (
            $this
                -> query
                -> has('async.existing_order_cb') 
            &&
            !$this
                -> query
                -> get('async.existing_order_cb')
        );
    }
}
