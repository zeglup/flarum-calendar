<?php

/*
 * This file is part of Flarum.
 *
 * For detailed copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

namespace Webbinaro\AdvCalendar\Event;

use Webbinaro\AdvCalendar\Event;
use Flarum\User\User;

class Created
{
    /**
     * @var Event
     */
    public $event;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Event $event
     * @param User $actor
     */
    public function __construct(Event $event, User $actor = null)
    {
        $this->event = $event;
        $this->actor = $actor;
    }
}
