<?php

namespace App\Models\Org\EdPart\Departments;

use App\Models\System\Relations\StudentsLink;
use App\Models\System\Repository\AdministrativeDocument;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class AcademicLeave extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'administrative_document_id',
        'reason',
        'expired_at',
        'returned_at',
        'vanilla_group_name',
        'persons_groups_link_id',
        'is_active',
    ];

    protected $casts = [
        'expired_at' => 'date:d.m.Y',
    ];

    public function administrativeDocument() {
        return $this -> belongsTo(AdministrativeDocument::class);
    }

    public function student() {
        return $this -> belongsTo(StudentsLink::class);
    }

    public function getExpiredAtAttribute($value) {
        return Carbon::parse($value) -> format('d.m.Y');
    }

    public function getReturnedAtAttribute($value) {
        return Carbon::parse($value) -> format('d.m.Y');
    }

    public function setExpiredAtAttribute($value) {
        $this -> attributes['expired_at'] = Carbon::createFromFormat('d.m.Y', $value);
    }

    public function setReturnedAtAttribute($value) {
        $this -> attributes['expired_at'] = Carbon::createFromFormat('d.m.Y', $value);
    }
}
