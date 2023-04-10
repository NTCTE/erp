<?php

namespace App\Models\Org\Library\Additionals;

use App\Models\System\Repository\Address\City;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Publisher extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname',
        'city_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
