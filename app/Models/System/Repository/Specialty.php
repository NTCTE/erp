<?php

namespace App\Models\System\Repository;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Specialty extends Model
{
    use AsSource;

    protected $fillable = [
        'code',
        'fullname',
    ];

    public function getFormattedAttribute() {
        return "{$this -> code} \"{$this -> fullname}\"";
    }
}
