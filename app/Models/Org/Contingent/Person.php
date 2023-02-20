<?php

namespace App\Models\Org\Contingent;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Attachment\Attachable;

class Person extends Model
{
    use AsSource, Attachable;

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
        'photo_id',
    ];

    static $sexs = [
        1 => 'Мужской',
        2 => 'Женский',
    ];

    // Блок отношений

    public function documents() {
        return $this -> hasMany(Document::class);
    }

    public function relatives() {
        return $this -> belongsToMany(Person::class, 'relation_links', 'person_id', 'relative_id');
    }

    public function passports() {
        return $this -> hasMany(Passport::class) -> orderByDesc('is_main');
    }

    // Блок аксессоров
    public function getFullnameAttribute(): string {
        return "{$this -> lastname} {$this -> firstname} {$this -> patronymic}";
    }

    public function getCorpEmailAttribute($value): string {
        return !empty($value) ? $this -> value : 'Не выдан';
    }

    public function getBirthdateAttribute($value): string {
        return !empty($value) ? Carbon::createFromFormat('Y-m-d', $value) -> format('d.m.Y') : 'Не указана';
    }
}
