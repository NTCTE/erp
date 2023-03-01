<?php

namespace App\Models\Org\EdPart\Departments;

use App\Models\Org\Contingent\Person;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Department extends Model
{
    use AsSource;

    protected $fillable = [
        'fullname',
        'manager_id',
    ];

    public function manager() {
        return $this -> belongsTo(Person::class);
    }

    public function groups() {
        return $this -> hasMany(Group::class);
    }
}
