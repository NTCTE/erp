<?php

namespace App\Models\System\Repository\Address;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Country extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname'
    ];

    public function getName() {
        $this->fullname;
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }
}
