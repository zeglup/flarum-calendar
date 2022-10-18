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
     * @var User
     */
    public $discussionUrl;

    /**
     * @param Event $event
     * @param string $discussionUrl
     * @param User|null $actor
     */
    public function __construct(Event $event, string $discussionUrl, User $actor = null)
    {
        $this->discussionUrl = $discussionUrl;
        $this->event = $event;
        $this->actor = $actor;
    }
}
