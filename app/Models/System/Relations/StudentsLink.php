<?php

namespace App\Models\System\Relations;

use App\Models\Org\Contingent\Person;
use App\Models\Org\EdPart\Departments\Group;
use App\Models\Org\EdPart\Departments\StudentsAction;
use App\Traits\Org\EdPart\Departments\StudentActionWritter;
use App\Traits\System\Dates;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class StudentsLink extends Model
{
    use AsSource, StudentActionWritter, Dates;

    protected $table = 'persons_groups_links';

    protected $fillable = [
        'person_id',
        'group_id',
        'enrollment_order_id',
        'academic_leave',
        'budget',
        'additionals',
    ];

    protected $casts = [
        'budget' => 'boolean',
        'academic_leave' => 'array',
    ];

    public $actionType = 8;
    public $actionAdditionals = null;
    public $last_group_id = null;
    public $administrative_document_id = null;

    public function group() {
        return $this -> belongsTo(Group::class);
    }

    public function person() {
        return $this -> belongsTo(Person::class);
    }

    public function actions() {
        return $this -> hasMany(StudentsAction::class, 'persons_groups_link_id');
    }

    public function setActions(int $type = 8, string $additionals = null, int $group_id = null, int $administrative_document_id = null) {
        $this -> actionType = $type;
        $this -> actionAdditionals = $additionals;
        $this -> last_group_id = $group_id ?? $this -> group_id;
        $this -> administrative_document_id = $administrative_document_id;

        return $this;
    }
}
