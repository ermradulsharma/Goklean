<?php

namespace App\Transformers\Api;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param $notification
     * @return array
     */
    public function transform($notification)
    {
        return [
            'id' => $notification->id,
            'title' => $notification->data['title'],
            'action' => $notification->data['action'],
            'message' => $notification->data['message'],
            'date' => Carbon::parse($notification->created_at)->shortRelativeToNowDiffForHumans(),
            'read' => $notification->read_at != null
        ];
    }
}
