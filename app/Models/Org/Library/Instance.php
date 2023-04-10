<?php

namespace App\Models\Org\Library;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Instance extends Model
{
    use AsSource;

    protected $fillable = [
        'book_set_id',
        'inventory_number',
        'rfid_signature'
    ];

}
