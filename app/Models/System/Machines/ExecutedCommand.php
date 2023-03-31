<?php

namespace App\Models\System\Machines;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class ExecutedCommand extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'machine_id',
        'command_id',
        'exit_code',
    ];

    public function machine() {
        return $this -> belongsTo(Machine::class);
    }

    public function command() {
        return $this -> belongsTo(Command::class);
    }
}
