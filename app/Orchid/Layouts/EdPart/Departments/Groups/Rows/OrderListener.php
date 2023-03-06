<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Rows;

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
                    -> horizontal()
                    -> placeholder('Связать с существующим Приказом о зачислении'),
                Relation::make('order.existing_order_id')
                    -> title('Приказ о зачислении')
                    -> placeholder('Выберите Приказ о зачислении')
                    -> fromModel(AdministrativeDocument::class, 'fullname', 'id')
                    -> searchColumns('fullname', 'number', 'date_at')
                    -> displayAppend('formatted')
                    -> horizontal()
                    -> required()
                    -> canSee($this -> query -> has('async.existing_order_cb') && $this -> query -> get('async.existing_order_cb')),
                Input::make('order.number')
                    -> title('Номер Приказа')
                    -> placeholder('Введите номер Приказа')
                    -> help('Не более 20 символов')
                    -> horizontal()
                    -> required()
                    -> canSee($this -> showInputs()),
                DateTimer::make('order.date_at')
                    -> title('Дата Приказа')
                    -> placeholder('Введите дату Приказа')
                    -> format('d.m.Y')
                    -> horizontal()
                    -> required()
                    -> canSee($this -> showInputs()),
                TextArea::make('order.fullname')
                    -> title('Название Приказа')
                    -> placeholder('Введите название Приказа')
                    -> help('Введите в одну строку, без сброса на следующие строки.')
                    -> horizontal()
                    -> required()
                    -> rows(5)
                    -> canSee($this -> showInputs()),
            ]),
        ];
    }

    private function showInputs() {
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
