<?php

namespace App\Models\System\Repository;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;

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

    public function orchidSchema() {
        $fields = [
            Input::make('doc.document_schema_id')
                -> type('hidden')
                -> value($this -> id),
        ];
        foreach ($this -> schema as $key => $value) {
            switch ($value['type']) {
                case 'text':
                case 'number':
                case 'email':
                case 'tel':
                    $fields[] = Input::make("doc.document.{$key}")
                        -> title(!is_null($value['title']) ? $value['title'] : '')
                        -> placeholder(!is_null($value['placeholder']) ? $value['placeholder'] : '')
                        -> help(!is_null($value['help']) ? $value['help'] : '')
                        -> type($value['type'])
                        -> required($value['required']);
                break;
                case 'date':
                case 'datetime-local':
                case 'month':
                case 'week':
                case 'time':
                    $format = $value['type'];
                    $fields[] = DateTimer::make("doc.document.{$key}")
                        -> title(!is_null($value['title']) ? $value['title'] : '')
                        -> placeholder(!is_null($value['placeholder']) ? $value['placeholder'] : '')
                        -> help(!is_null($value['help']) ? $value['help'] : '')
                        -> required($value['required'])
                        -> format((function () use ($format) {
                            switch ($format) {
                                case 'date':
                                    return 'd.m.Y';
                                case 'datetime-local':
                                    return 'd.m.Y H:i';
                                case 'month':
                                    return 'm.Y';
                                case 'week':
                                    return 'W';
                                case 'time':
                                    return 'H:i';
                            }
                        })());
                break;
            }
        }
        return $fields;
    }
}
