<?php

namespace App\Models\Org\Library\Actions;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class TakenInstance extends Model
{
    use AsSource;

    protected $fillable = [
        'person_id',
        'instance_id',
        'deadline',
        'return_date'
    ];
}
