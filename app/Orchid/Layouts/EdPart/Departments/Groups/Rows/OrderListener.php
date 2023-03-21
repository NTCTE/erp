<?php

namespace App\Orchid\Layouts\EdPart\Departments\Groups\Rows;

use App\Models\Org\EdPart\Departments\Group;
use App\Models\System\Relations\AdministativeDocumentsLinks;
use App\Models\System\Repository\AdministrativeDocument;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
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
        if (empty(request() -> route() -> parameter('group'))) {
            return [
                FacadesLayout::rows([
                    CheckBox::make('async.existing_order_cb')
                        -> sendTrueOrFalse()
                        -> title('Связь')
                        -> placeholder('Связать с существующими Приказами')
                        -> help('Эти данные устанавливаются единожды, редактирование связи с каноничным Приказом невозможно, возможно только редактирование данных Приказа через репозиторий административных документов.')
                        -> horizontal(),
                    Matrix::make('orders')
                        -> title('Приказы')
                        -> columns([
                            'Приказ' => 'id',
                            'Описание Приказа' => 'description',
                        ])
                        -> fields([
                            'id' => Relation::make()
                                -> placeholder('Выберите Приказ...')
                                -> fromModel(AdministrativeDocument::class, 'fullname', 'id')
                                -> searchColumns('fullname', 'number', 'date_at')
                                -> displayAppend('formatted')
                                -> required(),
                            'description' => TextArea::make()
                                -> placeholder('Введите описание Приказа...')
                                -> required(),
                        ])
                        -> canSee($this -> query -> has('async.existing_order_cb') && $this -> query -> get('async.existing_order_cb')),
                    Matrix::make('orders')
                        -> title('Приказы')
                        -> columns([
                            'Номер Приказа' => 'number',
                            'Дата Приказа' => 'date_at',
                            'Название Приказа' => 'fullname',
                            'Описание Приказа' => 'description',
                        ])
                        -> fields([
                            'date_at' => DateTimer::make()
                                -> placeholder('Выберите дату Приказа...')
                                -> format('d.m.Y')
                                -> required(),
                            'number' => TextArea::make()
                                -> placeholder('Введите номер Приказа...')
                                -> required(),
                            'fullname' => TextArea::make()
                                -> placeholder('Введите полное название Приказа...')
                                -> required(),
                            'description' => TextArea::make()
                                -> placeholder('Введите описание Приказа...')
                                -> required(),
                        ])
                        -> canSee($this -> showInputs()),
                ])
                    -> title('Приказы'),
            ];
        } else {
            return [
                FacadesLayout::table('orders', [
                    TD::make('formatted', 'Полное наименование Приказа'),
                    TD::make('description', 'Описание Приказа')
                        -> render(function (AdministrativeDocument $document) {
                            return AdministativeDocumentsLinks::where('administrative_document_id', $document -> id)
                                -> where('signed_id', request() -> route() -> parameter('group'))
                                -> where('signed_type', Group::class)
                                -> first()
                                -> description;
                        }),
                ]),
            ];
        }
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
