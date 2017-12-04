<?php

namespace App\Listeners;

use App\Events\CreatePlug;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        Log::info(12);
        Cache::forget('plug_index_wa');
        Cache::forget('plug_index_tmw');
        Cache::forget('plug_index_plug');
        Cache::forget('plug_index_elvuis');
        Cache::forget('plug_index_recent_plugs');
        Cache::forget('plug_index_census');
        \Illuminate\Support\Facades\Cache::forget('plug_index_download_plugs_this_mouth');
        \Illuminate\Support\Facades\Cache::forget('plug_index_download_plugs');
        \Illuminate\Support\Facades\Cache::forget('plug_index_total_person');
    }
}
