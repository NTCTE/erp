<?php

namespace App\Traits\System;

use Carbon\Carbon;

trait Dates {
    public function getCreatedAtAttribute($value) {
        return Carbon::parse($value)
            -> setTimezone(env('TIMEZONE'))
            -> format('d.m.Y в H:i:s');
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::parse($value)
            -> setTimezone(env('TIMEZONE'))
            -> format('d.m.Y в H:i:s');
    }
}