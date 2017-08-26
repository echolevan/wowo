<?php

namespace App\Listeners;

use App\Events\CreatePlug;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class CreatePlugListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreatePlug  $event
     * @return void
     */
    public function handle(CreatePlug $event)
    {
        //
        Cache::forget('plug_index_wa');
        Cache::forget('plug_index_tmw');
        Cache::forget('plug_index_plug');
        Cache::forget('plug_index_recent_plugs');
        Cache::forget('plug_index_census');
    }
}
