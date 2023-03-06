<?php

namespace App\Models\System\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AdministrativeDocument extends Model
{
    use AsSource;

    protected $fillable = [
        'type',
        'fullname',
        'number',
        'date_at',
    ];

    static $types = [
        1 => 'Приказ',
        2 => 'Распоряжение',
        3 => 'Постановление',
        4 => 'Служебная записка',
    ];

    public function getFormattedAttribute() {
        return self::$types[$this -> type] . " № {$this -> number} от {$this -> date_at} г. «{$this -> fullname}»";
    }

    public function getDateAtAttribute($value) {
        return Carbon::createFromFormat('Y-m-d', $value) -> format('d.m.Y');
    }

}
