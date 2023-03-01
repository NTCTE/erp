<?php

namespace App\Models\Org\EdPart\Departments;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

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
        return '(добавить правильное отображение группы)';
    }

    public function getEnrollmentDateAttribute($value) {
        return Carbon::createFromFormat('Y-m-d', $value) -> format('d.m.Y');
    }
}
