<?php

namespace Webbinaro\AdvCalendar\Api\Controllers;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Discussion\Command\ReadDiscussion;
use Flarum\Discussion\Command\StartDiscussion;
use Flarum\Discussion\Discussion;
use Flarum\Http\RequestUtil;
use Flarum\Http\UrlGenerator;
use Flarum\Post\Command\PostReply;
use Flarum\Post\CommentPost;
use Flarum\Post\Post;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Psr\Http\Message\ServerRequestInterface;
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
     * @var BusDispatcher
     */
    protected $bus;

    /**
     * @param UrlGenerator $generator
     */
    protected $generator;

    public function __construct(Dispatcher $events, BusDispatcher $bus, UrlGenerator $generator)
    {
        $this->generator = $generator;
        $this->bus = $bus;
        $this->events = $events;
    }

    public $serializer = EventSerializer::class;
    public $include = ['user'];

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $ipAddress = $request->getAttribute('ipAddress');
        $actor->assertRegistered();
        if(!$actor->can('event.create')){
            throw new PermissionDeniedException();
        }
        $requestData = Arr::get($request->getParsedBody(), 'data.attributes');
        $event = Event::build( $requestData['name'], $requestData['description'], $actor->id, $requestData['event_start']);

        if(true === $event->saveOrFail()) {


            $event_start = new Carbon($requestData['event_start']);
            $event_start->setTimezone(new CarbonTimeZone('Europe/Paris'));
            $event_start::setLocale('fr');

            $json ='{"data":{"type":"discussions","attributes":{"title":"' . $event_start->format('Y/m/d') . ' - '  .addslashes($requestData['name']) . '","content":"#### Briefing\n\n**Date :** ' . $event_start->translatedFormat('l jS F') . '\n**Heure :** ' . $event_start->format('H:i') . '\n**Mission :** ' . addslashes($requestData['description']) . '\n**Contexte :**\n**Briefing :**\n**Menaces :**\n**Objectif(s) :**\n**Emport(s) :**\n**Communications :**\n**Météorologie :**\n\n---\n\n#### Debriefing\n\n- Type de vol : *mission ou entrainement ou simulateur*\n- Participants :\n- Temps de vol :\n- Analyse des événements :\n\n- Points positifs :\n\n- Points négatifs :\n\n- Leçon(s) apprise(s) :\n\n- Axe(s) d\'amélioration :"},"relationships":{"tags":{"data":[{"type":"tags","id":"2"}]}}}}';
            $data = json_decode($json, true);

            $discussion = $this->bus->dispatch(
                new StartDiscussion($actor, Arr::get($data, 'data', []), $ipAddress)
            );

            if ($actor->exists) {
                $this->bus->dispatch(
                    new ReadDiscussion($discussion->id, $actor, 1)
                );
            }

            $this->events->dispatch(
                new Event\Created($event, $this->generator->to('forum')->route('discussion', ['id' => $discussion->id]), $actor)
            );

        }
        return $event;
    }
}
