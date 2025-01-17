<?php

namespace Webbinaro\AdvCalendar\Api\Controllers;

use Flarum\Api\Controller\AbstractShowController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tobscure\JsonApi\Document;
use Webbinaro\AdvCalendar\Api\Serializers\EventSerializer;
use Webbinaro\AdvCalendar\Event;

class EventsShowController extends AbstractShowController
{
    public $serializer = EventSerializer::class;
    public $include = ['user'];
    protected function data(Request $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $actor->assertCan('event.view');

        $id = Arr::get($request->getQueryParams(), 'id');
        return Event::findOrFail($id);
    }
}
