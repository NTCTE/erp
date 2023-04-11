<?php

namespace App\Models\System\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $casts = [
        'date_at' => 'date:d.m.Y',
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

    public function getShortAttribute() {
        return self::$types[$this -> type] . " № {$this -> number} от {$this -> date_at} г.";
    }

    public function getDateAtAttribute($value) {
        return Carbon::parse($value) -> format('d.m.Y');
    }

    public function setDateAtAttribute($value) {
        $this -> attributes['date_at'] = Carbon::createFromFormat('d.m.Y', $value);
    }
}
