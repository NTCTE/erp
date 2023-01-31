<?php

namespace App\Models\Org\Contingent;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Illuminate\Support\Str;

class Person extends Model
{
    use AsSource;

    protected $table = 'persons';

    protected $fillable = [
        'uuid',
        'user_id',
        'lastname',
        'firstname',
        'patronymic',
        'email',
        'corp_email',
        'tel',
        'birthdate',
        'snils',
        'inn',
        'hin',
        'sex',
        'workplace_id',
        'position_id',
    ];

    // Блок аксессоров
    public function getFullnameAttribute() {
        return "{$this -> lastname} {$this -> firstname} {$this -> patronymic}";
    }

    public function getCorpEmailAttribute($value) {
        return !empty($value) ? $this -> value : 'Не выдан';
    }

    public function getBirthdateAttribute($value) {
        if (!empty($value)) {
            $date = explode('-', $value);
            return "{$date[2]}.{$date[1]}.{$date[0]}";
        } else return '';
    }

    // Блок мутаторов
    public function setBirthdateAttribute($value) {
        $date = explode('.', $value);
        return "{$date[2]}-{$date[1]}-{$date[0]}";
    }

    public function setUuidAttribute() {
        return Str::uuid();
    }
}
