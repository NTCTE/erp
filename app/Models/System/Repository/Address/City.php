<?php

namespace App\Models\System\Repository\Address;

use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use AsSource, Filterable;

    protected $fillable = [
        'fullname',
        'region_id'
    ];

    protected $allowedFilters = [
        'fullname'
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
