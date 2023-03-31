<?php

namespace App\Models\System\Machines;

use App\Traits\Org\Contingent\UuidSetter;
use App\Traits\System\Dates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Machine extends Model
{
    use HasFactory, AsSource, Dates;

    protected $fillable = [
        'ip_address'
    ];

    public function executed_commands() {
        return $this -> hasManyThrough(Command::class, ExecutedCommand::class, 'machine_id', 'id', 'id', 'command_id')
            -> select('commands.id', 'command', 'executed_commands.created_at');
    }

    public function not_executed_commands() {
        return Command::whereNotIn('id', function($query) {
            $query -> select('command_id')
                -> from('executed_commands')
                -> where('machine_id', $this -> id);
        }) -> get(['id', 'command']);
    }
}
