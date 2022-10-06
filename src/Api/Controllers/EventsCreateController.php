<?php

namespace Webbinaro\AdvCalendar\Api\Controllers;

use Flarum\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Webbinaro\AdvCalendar\Api\Serializers\EventSerializer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tobscure\JsonApi\Document;
use Webbinaro\AdvCalendar\Event;
use Flarum\User\Exception\PermissionDeniedException;
use Illuminate\Contracts\Events\Dispatcher;

class EventsCreateController extends AbstractCreateController
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

    public $serializer = EventSerializer::class;
    public $include = ['user'];

    protected function data(Request $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $actor->assertRegistered();
        if(!$actor->can('event.create')){
            throw new PermissionDeniedException();
        }
        $requestData = Arr::get($request->getParsedBody(), 'data.attributes');
        $event = Event::build( $requestData['name'], $requestData['description'], $actor->id, $requestData['event_start']);
        if(true === $event->saveOrFail()) {
            $this->events->dispatch(
                new Event\Created($event, $actor)
            );
        }
        return $event;
    }
}
