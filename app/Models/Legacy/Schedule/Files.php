<?php

namespace App\Models\Legacy\Schedule;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\AsSource;

class Files extends Model
{
    use AsSource, Attachable;

    protected $table = 'legacyScheduleFiles';

    protected $fillable = [
        'schedule_id',
        'attachment_id'
    ];

    public function url() {
        return $this -> belongsTo(Attachment::class, 'attachment_id');
    }
}
