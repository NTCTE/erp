<?php

namespace App\Models\Org\Library\Actions;

use App\Models\Org\Contingent\Person;
use App\Models\Org\Library\Instance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class TakenInstance extends Model
{
    use AsSource;

    protected $fillable = [
        'person_id',
        'instance_id',
        'deadline',
        'return_date'
    ];

    public function persons(): BelongsTo {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    public function instances() {
        return $this->belongsTo(Instance::class, 'instance_id', 'id');
    }

    public function getPersonFullnameAttribute(): string {
        return $this->persons->getFullnameAttribute();
    }

    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = Carbon::parse($value);
    }

    public function setReturnDateAttribute($value)
    {
        $this->attributes['return_date'] = Carbon::parse($value);
    }

    public function getDeadlineAttribute($value):? string {
        return !empty($value) ? Carbon::createFromFormat('Y-m-d', $value) -> format('d.m.Y') : null;
    }

    public function getReturnDateAttribute($value):? string {
        return !empty($value) ? Carbon::createFromFormat('Y-m-d', $value) -> format('d.m.Y') : null;
    }

}
