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
 * @property Carbon $event_end
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property int $mission_id
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
        'event_start',
        'event_end'
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
     * @param  $event_end
     *
     * @return static
     */
    public static function build($name, $description, $actorId, $event_start, $event_end, $mission_id)
    {
        $event = new static();

        $event->name = $name;
        $event->description = $description;
        $event->user_id = $actorId;
        $event->mission_id = $mission_id;
        $event->event_start = new Carbon($event_start);
        $event->event_end = new Carbon($event_end);

        return $event;
    }

    /**
     * Used by update controller to keep and conversion logic here.
     * @param $name
     * @param $description
     * @param $actorId
     * @param  $event_start
     * @param  $event_end
     *
     * @return static
     */
    public function replace($name, $description, $event_start, $event_end)
    {
        $this->name = $name;
        $this->description = $description;
        $this->event_start = new Carbon($event_start);
        $this->event_end = new Carbon($event_end);

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
