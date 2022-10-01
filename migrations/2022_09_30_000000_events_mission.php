<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::addColumns('events', [
    'mission_id' => ['integer', 'unsigned' => true]
]);
