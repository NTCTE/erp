<?php

namespace App\Models\Org\Contingent;

use App\Models\System\Repository\PassportIssuer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Passport extends Model
{
    use AsSource;

    protected $fillable = [
        'series',
        'number',
        'passport_issuer_id',
        'date_of_issue',
        'birthplace',
        'is_main',
        'person_id',
    ];

    public function getFullNumberAttribute(): string {
        return "{$this -> series} {$this -> number}";
    }

    public function getIsMainAttribute($value): string {
        return $value ? 'Да' : 'Нет';
    }

    public function getDateOfIssueAttribute($value): string {
        return Carbon::createFromFormat('Y-m-d', $value) -> format('d.m.Y');
    }

    public function getPassportIssuerAttribute(): string {
        return $this -> hasOne(PassportIssuer::class, 'id', 'passport_issuer_id') -> first() -> formatted;
    }
}
