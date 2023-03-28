<?php

namespace App\Models\System\Machines;

use App\Traits\Org\Contingent\UuidSetter;
use App\Traits\System\Dates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Command extends Model
{
    use HasFactory, AsSource, UuidSetter, Dates;

    protected $fillable = [
        'command',
        'uuid',
    ];

    protected $hidden = [
        'laravel_through_key'
    ];

    public function seeded() {
        return $this -> hasMany(ExecutedCommand::class);
    }

    public function getSuccessesAttribute() {
        return $this -> seeded() -> count();
    }
}
