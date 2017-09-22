<?php

namespace App\Jobs;

use App\Plug;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPlugCheckEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $plug;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Plug $plug)
    {
        //
        $this->plug = $plug;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Log::info(json_encode($this->plug));
        Mail::to(env('PLUG_CHECK_EMAIL'))->send(new \App\Mail\sendCheckPlug($this->plug));
    }
}
