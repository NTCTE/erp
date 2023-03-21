<?php

namespace App\Models\Org\EdPart\Departments;

use App\Models\System\Relations\StudentsLink;
use App\Models\System\Repository\AdministrativeDocument;
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
        'vanilla_group_name',
        'persons_groups_link_id',
        'is_active',
    ];

    public function administrativeDocument() {
        return $this -> belongsTo(AdministrativeDocument::class);
    }

    public function student() {
        return $this -> belongsTo(StudentsLink::class);
    }
}
