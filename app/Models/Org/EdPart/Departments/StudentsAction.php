<?php

namespace App\Models\Org\EdPart\Departments;

use App\Models\System\Relations\StudentsLink;
use App\Traits\System\Dates;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class StudentsAction extends Model
{
    use AsSource, Dates;

    protected $fillable = [
        'persons_groups_link_id',
        'group_id',
        'vanilla_name',
        'type',
        'additionals',
    ];

    static $types = [
        1 => 'Поступление',
        2 => 'Перевод в другую группу',
        3 => 'Отчисление',
        4 => 'Восстановление',
        5 => 'Выпуск',
        6 => 'Выход в академический отпуск',
        7 => 'Возвращение из академического отпуска',
        8 => 'Дополнение студенческой информации',
    ];

    public function group() {
        return $this -> belongsTo(Group::class);
    }

    public function student() {
        return $this -> belongsTo(StudentsLink::class);
    }

    public function getTypeAttribute($value) {
        return self::$types[$value];
    }

    public function getAdditionalsAttribute($value) {
        return !empty($value) ? $value : 'Нет дополнительной информации';
    }
}
