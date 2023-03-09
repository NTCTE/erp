<?php

namespace App\Traits\Org\Contingent;

use Illuminate\Support\Str;

trait UuidSetter {
    public static function bootUuidSetter() {
        static::creating(function ($model) {
            $model -> uuid = (string) Str::uuid();
        });
    }
}