<?php

namespace App\Models\System\Repository;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Language extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname'
    ];

}
