<?php

use Flarum\Database\Migration;

return Migration::dropColumns('events', [
    'event_end' => ['datetime']
]);
