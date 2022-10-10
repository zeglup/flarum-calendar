<?php

namespace Webbinaro\AdvCalendar\Api\Controllers;

use Illuminate\Support\Arr;
use Flarum\Api\Controller\AbstractShowController;
use Webbinaro\AdvCalendar\Api\Serializers\EventSerializer;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tobscure\JsonApi\Document;
use Webbinaro\AdvCalendar\Event;
use Webbinaro\AdvCalendar\EventSubscribers;

class EventsSubscribeController extends AbstractShowController
{
    public $serializer = EventSerializer::class;

    protected function data(Request $request, Document $document)
    {
        $event_id = Arr::get($request->getQueryParams(), 'id');
        $requestData = Arr::get($request->getParsedBody(), 'data.attributes');
        $user_id = $requestData['user_id'];

        $event = Event::findOrFail($event_id);
        $eventSubscriber = EventSubscribers::whereIn('user_id', [ $user_id ])->whereIn('event_id', [ $event_id ])->first();
        if(null === $eventSubscriber) {
            $eventSubscriber = EventSubscribers::build($event_id, $user_id);
            $eventSubscriber->save();
        }
        return $event;
    }
}
