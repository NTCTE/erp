<?php

namespace App\Traits\Org\EdPart\Departments;

use App\Models\Org\EdPart\Departments\Group;
use App\Models\Org\EdPart\Departments\StudentsAction;

trait StudentActionWritter {
    public static function writeAction(
        int $persons_groups_link_id,
        int $group_id,
        string $vanilla_name,
        int $type = 8,
        string $additionals = null,
        int $administrative_document_id = null
    ) {
        StudentsAction::create([
            'persons_groups_link_id' => $persons_groups_link_id,
            'group_id' => $group_id,
            'vanilla_name' => $vanilla_name,
            'type' => $type,
            'additionals' => $additionals,
            'administrative_document_id' => $administrative_document_id,
        ]);
    }

    public static function bootStudentActionWritter() {
        static::created(function ($model) {
            self::writeAction(
                $model -> id,
                $model -> last_group_id,
                Group::find($model -> last_group_id) -> name(),
                $model -> actionType,
                $model -> actionAdditionals,
                $model -> administrative_document_id
            );
        });
        static::updated(function ($model) {
            self::writeAction(
                $model -> id,
                $model -> last_group_id,
                Group::find($model -> last_group_id) -> name(),
                $model -> actionType,
                $model -> actionAdditionals,
                $model -> administrative_document_id
            );
        });
    }
}