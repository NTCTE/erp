<?php

namespace App\Traits\System\Listeners;

trait Order {
    public function asyncUpdateAppendGroup($async): array {
        $ret = [
            'async.existing_order_cb' => $async['existing_order_cb'],
        ];

        if (!empty($async['existing_order']))
            $ret['async.existing_order'] = $async['existing_order'];

        return $ret;
    }
}