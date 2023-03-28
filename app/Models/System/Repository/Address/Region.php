<?php

namespace App\Models\System\Repository\Address;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class Region extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname',
        'country_id'
    ];

    public function country(): ?BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
