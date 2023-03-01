<?php

namespace App\Models\System\Relations;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class StudentsLink extends Model
{
    use AsSource;

    protected $table = 'groups_persons';

    protected $fillable = [
        'group_id',
        'person_id',
        'enrollment_date',
        'expilled_date',
    ];

}
