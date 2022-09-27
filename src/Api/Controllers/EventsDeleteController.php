<?php


namespace Webbinaro\AdvCalendar\Api\Controllers;

use Flarum\Api\Controller\AbstractDeleteController;
use Flarum\User\Exception\PermissionDeniedException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface as Request;
use Webbinaro\AdvCalendar\Event;
use Webbinaro\AdvCalendar\Event as AdvEvent;

class EventsDeleteController extends AbstractDeleteController
{
    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    protected function delete(Request $request)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $actor->assertRegistered();
        $event = AdvEvent::findOrFail($id);
        if(! $actor->can('event.moderate') && $actor->id !== $event->user->id ) {
            throw new PermissionDeniedException("non moderator unowned event");
        }
        if(true === $event->delete()) {
            $this->events->dispatch(
                new Event\Deleted($event, $actor)
            );
        }
    }
}
