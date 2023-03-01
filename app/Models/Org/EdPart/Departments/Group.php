<?php

namespace App\Models\Org\EdPart\Departments;

use App\Models\Org\Contingent\Person;
use App\Models\System\Relations\StudentsLink;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Illuminate\Support\Str;

class Group extends Model
{
    use AsSource;

    protected $fillable = [
        'uuid',
        'enrollment_date',
        'training_period',
        'shortname',
        'department_id',
        'curator_id',
        'archived',
    ];

    public function name() {
        $period = $this -> getActualPeriod();
        $period = $period <= $this -> training_period ? $period : $this -> training_period;
        
        return Str::replace('#', $period, $this -> shortname);
    }

    public function getActualPeriod() {
        return Carbon::createFromFormat(
            'd.m.Y',
            $this
                -> enrollment_date
            )
        -> diffInYears(Carbon::now()) + 1;
    }

    public function getEnrollmentDateAttribute($value) {
        return Carbon::createFromFormat('Y-m-d', $value) -> format('d.m.Y');
    }

    public function students() {
        return $this -> hasManyThrough(Person::class, StudentsLink::class, 'group_id', 'id', 'id', 'person_id');
    }
}
