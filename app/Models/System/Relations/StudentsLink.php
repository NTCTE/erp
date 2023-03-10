<?php

namespace App\Models\System\Relations;

use App\Models\Org\EdPart\Departments\Group;
use App\Traits\Org\EdPart\Departments\StudentActionWritter;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class StudentsLink extends Model
{
    use AsSource, StudentActionWritter;

    protected $table = 'persons_groups_links';

    protected $fillable = [
        'person_id',
        'group_id',
        'enrollment_order_id',
        'academic_leave',
        'budget',
        'additionals',
    ];

    public $actionType = 8;
    public $actionAdditionals = null;

    public function group() {
        return $this -> belongsTo(Group::class);
    }

    public function setActions(int $type = 8, string $additionals = null) {
        $this -> actionType = $type;
        $this -> actionAdditionals = $additionals;

        return $this;
    }
}
