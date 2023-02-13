<?php

namespace App\Models\Org\Contingent;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class RelationLink extends Model
{
    use AsSource;

    protected $fillable = [
        'person_id',
        'relative_id',
        'relation_type_id',
    ];
}
