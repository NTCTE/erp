<?php

namespace App\Models\System\Relations;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class StudentsLink extends Model
{
    use AsSource;

    protected $table = 'persons_groups_links';

    protected $fillable = [
        'person_id',
        'group_id',
        'enrollment_order_id',
        'academic_leave',
        'budget',
        'additionals',
    ];

}
