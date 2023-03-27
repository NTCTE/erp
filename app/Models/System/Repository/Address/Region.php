<?php

namespace App\Models\System\Repository\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Region extends Model
{
    use AsSource;

    protected $fillable = [
      'fullname',
      'country_id'
    ];
}
