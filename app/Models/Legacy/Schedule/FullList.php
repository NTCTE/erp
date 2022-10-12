<?php

namespace App\Models\Legacy\Schedule;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class FullList extends Model
{
    use AsSource;

    protected $table = 'legacySchedule';

    protected $fillable = [
        'date_at',
    ];

    public function getDateAtAttribute($value) {
        $d = explode('-', $value);
        return "{$d[2]}.{$d[1]}.{$d[0]}";
    }

    public function files() {
        return $this -> hasMany(Files::class, 'schedule_id');
    }
}
