<?php

namespace App\Orchid\Layouts\EdPart\Departments;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Group;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class GroupsTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    public $target;

    public function __construct(string $title, string $target) {
        $this -> target = $target;
        $this -> title($title);
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Название группы')
                -> render(function(Group $group) {
                    return $group -> name();
                }),
            TD::make('enrollment_date', 'Дата зачисления'),
            TD::make('training_period', 'Срок обучения (полных лет)'),
            TD::make('curator_id', 'Куратор')
                -> render(function(Group $group) {
                    $person = Person::find($group -> curator_id);
                    return Link::make($person -> fullname)
                        -> route('org.contingent.person', $person);
                }),
        ];
    }
}
