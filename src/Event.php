<?php

namespace Webbinaro\AdvCalendar;

use Flarum\Database\AbstractModel;
use Flarum\Database\ScopeVisibilityTrait;
use Flarum\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property User $user
 * @property int $user_id
 * @property Carbon $event_start
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Event extends AbstractModel
{
    use ScopeVisibilityTrait;
    /**
     * {@inheritdoc}
     */
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'event_start'
    ];

    // The default sort field and order to use.
    public $sort = ['event_start' => 'asc'];

    // The fields that are available to be sorted by.
    public $sortFields = ['name'];

    /**
     * @param $name
     * @param $description
     * @param $actorId
     * @param  $event_start
     *
     * @return static
     */
    public static function build($name, $description, $actorId, $event_start)
    {
        $event = new static();

        $event->name = $name;
        $event->description = $description;
        $event->user_id = $actorId;
        $event->event_start = new Carbon($event_start);

        return $event;
    }

    /**
     * Used by update controller to keep and conversion logic here.
     * @param $name
     * @param $description
     * @param $actorId
     * @param  $event_start
     *
     * @return static
     */
    public function replace($name, $description, $event_start)
    {
        $this->name = $name;
        $this->description = $description;
        $this->event_start = new Carbon($event_start);

        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
