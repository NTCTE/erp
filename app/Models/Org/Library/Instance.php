<?php

namespace App\Models\Org\Library;

use App\Models\Org\Library\Actions\TakenInstance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class Instance extends Model
{
    use AsSource;

    protected $fillable = [
        'book_set_id',
        'inventory_number',
        'rfid_signature'
    ];

    public function bookSet(): BelongsTo
    {
        return $this->belongsTo(BookSet::class);
    }

    public function takenInstance(): BelongsTo
    {
        return $this->belongsTo(TakenInstance::class, 'id', 'person_id');
    }

}
