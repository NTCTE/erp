<?php

namespace App\Models\System\Repository;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class DocumentSchema extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname',
        'schema',
        'readonly',
    ];

    protected $casts = [
        'schema' => 'array'
    ];

    static $types = [
        'text' => 'Текстовое поле',
        'number' => 'Числовое поле',
        'email' => 'Адрес электронной почты',
        'tel' => 'Номер телефона',
        'date' => 'Дата',
        'datetime-local' => 'Дата и время',
        'month' => 'Месяц',
        'week' => 'Неделя',
        'time' => 'Время',
    ];

    public function getReadonlyAttribute($value) {
        return boolval($value) ? 'Да' : 'Нет';
    }
}
