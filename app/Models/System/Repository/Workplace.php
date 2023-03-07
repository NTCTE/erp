<?php

namespace App\Models\System\Repository;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Workplace extends Model
{
    use AsSource;

    public $timestamps = false;

    protected $fillable = [
        'fullname',
        'tel',
        'email',
    ];
}