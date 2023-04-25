<?php

namespace App\Models\System\Repository\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }
}
