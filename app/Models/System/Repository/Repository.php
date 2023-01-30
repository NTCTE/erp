<?php

namespace App\Models\System\Repository;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Repository extends Model
{
    use AsSource;

    protected $table = 'repository';
}
