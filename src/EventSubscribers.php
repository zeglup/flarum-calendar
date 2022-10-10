<?php

namespace Webbinaro\AdvCalendar;

use Flarum\Database\AbstractModel;

/**
 * @property int $user_id
 * @property int $event_id
 */
class EventSubscribers  extends AbstractModel
{
    /**
     * @param $event_id
     * @param $user_id
     * @return static
     */
    public static function build($event_id, $user_id)
    {
        $eventSubsriber = new static();

        $eventSubsriber->user_id = $user_id;
        $eventSubsriber->event_id = $event_id;

        return $eventSubsriber;
    }
}
