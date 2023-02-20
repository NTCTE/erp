<?php

namespace App\Orchid\Layouts\Contingent\Person;

use App\Models\Org\Contingent\Person;
use App\Models\Org\Contingent\RelationLink;
use App\Models\System\Repository\RelationType;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RelativesTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'relatives';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('fullname', 'ФИО')
                -> render(function(Person $person) {
                    return Link::make($person -> fullname)
                        -> route('org.contingent.person', $person);
                }),
            TD::make('relation_type', 'Тип родства')
                -> render(function(Person $person) {
                    return RelationType::where('id', RelationLink::where([
                            ['person_id', '=', request() -> id],
                            ['relative_id', '=', $person -> id],
                        ]) -> first() -> relation_type_id)
                        -> first()
                        -> fullname;
                }),
            TD::make('actions', 'Действия')
                -> render(function(Person $person) {
                    return DropDown::make('')
                        -> icon('options-vertical')
                        -> list([
                            ModalToggle::make('Изменить связь')
                                -> modal('editRelationModal')
                                -> method('editRelation')
                                -> parameters([
                                    'rel_id' => RelationLink::where([
                                        ['person_id', '=', request() -> id],
                                        ['relative_id', '=', $person -> id],
                                    ]) -> first() -> id,
                                ])
                                -> icon('pencil'),
                            Button::make('Удалить связь')
                                -> confirm('Вы уверены, что хотите удалить связь? Это действие необратимо. Персона, с которой удаляется связь, не будет удалена.')
                                -> method('deleteRelation')
                                -> parameters([
                                    'rel_id' => RelationLink::where([
                                        ['person_id', '=', request() -> id],
                                        ['relative_id', '=', $person -> id],
                                    ]) -> first() -> id,
                                    'purge' => false,
                                ])
                                -> icon('trash'),
                            Button::make('Удалить связь (с персоной)')
                                -> confirm('Вы уверены, что хотите удалить связь? Это действие необратимо. Персона, с которой удаляется связь, будет удалена.')
                                -> method('deleteRelation')
                                -> parameters([
                                    'rel_id' => RelationLink::where([
                                        ['person_id', '=', request() -> id],
                                        ['relative_id', '=', $person -> id],
                                    ]) -> first() -> id,
                                    'purge' => true,
                                ])
                                -> icon('trash'),
                        ]);
                })
                    -> canSee(Auth::user() -> hasAccess('org.contingent.write')),
        ];
    }
}
