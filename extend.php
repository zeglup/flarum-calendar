<?php

/*
 * This file is part of webbinaro/flarum-calendar.
 *
 * Copyright (c) 2020 Eddie Webbinaro.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Webbinaro\AdvCalendar;

use Flarum\Api\Controller\CreateUserController;
use Flarum\Api\Controller\ListPostsController;
use Flarum\Api\Controller\ListUsersController;
use Flarum\Api\Controller\ShowDiscussionController;
use Flarum\Api\Controller\ShowUserController;
use Flarum\Api\Controller\UpdateUserController;
use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\Extend;
use Flarum\User\User;
use FoF\Masquerade\Answer;
use FoF\Masquerade\Api\Serializers\AnswerSerializer;
use FoF\Masquerade\Api\Serializers\FieldSerializer;
use FoF\Masquerade\ForumAttributes;
use FoF\Masquerade\UserAttributes;
use Webbinaro\AdvCalendar\Api\Controllers as ControllersAlias;
use Illuminate\Events\Dispatcher;
use Webbinaro\AdvCalendar\Api\Serializers\EventSerializer;
use Webbinaro\AdvCalendar\Integrations\EventResourceRegister;
use Webbinaro\AdvCalendar\Integrations\SitemapsResource;
use Webbinaro\AdvCalendar\Listeners;

return [

//    (new Extend\Model(User::class))
//        ->belongsToMany('event', Content\Event::class, 'event_user', 'user_id', 'event_id'),


    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less')
        ->route('/events', 'advevents')
        ->route(
            '/events/{id}[/{filter:[0-9]*}]',
            'advevent',
            Content\Event::class),



    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    //API Routes
    (new Extend\Routes('api'))
    	->get('/events','events.index', ControllersAlias\EventsListController::class)
    	->get('/events/{id}','events.show', ControllersAlias\EventsShowController::class)
    	->post('/events','events.create', ControllersAlias\EventsCreateController::class)
        ->post('/events/{id}/subscribe','events.subscribe', ControllersAlias\EventsSubscribeController::class)
        ->patch('/events/{id}','events.edit', ControllersAlias\EventsUpdateController::class)
        ->delete('/events/{id}','events.delete', ControllersAlias\EventsDeleteController::class),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    (new Extend\Event)
        ->subscribe(Listeners\AdvEventListener::class),

    (new Extend\ApiController(ShowUserController::class))
        ->addInclude('events'),


    (new Extend\ApiController(UpdateUserController::class))
        ->addInclude('events'),

    (new Extend\ApiController(CreateUserController::class))
        ->addInclude('event'),

    (new Extend\ApiController(ListUsersController::class))
        ->addInclude('event'),


    (new Extend\ApiSerializer(BasicUserSerializer::class))
        ->hasMany('event', EventSerializer::class)
        ->attributes(function (BasicUserSerializer $serializer, User $user): array {
                $user->setRelation('event', null);
            return [];
        }),

    (new Extend\ApiSerializer(UserSerializer::class))
        ->attributes(UserAttributes::class),

    new EventResourceRegister(),

];
