<?php

namespace App\Models\System\Repository\Address;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname',
        'region_id'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
