<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable(
    'users_event',
    function (Blueprint $table) {
        $table->integer('user_id')->unsigned();
        $table->integer('event_id')->unsigned();
        $table->primary(['user_id', 'event_id']);
    }
);


