<?php

namespace App\Jobs;

use App\Ad;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SenfAdEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $ad;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Ad $ad)
    {
        //
        $this->ad = $ad;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Mail::to(env('PLUG_CHECK_EMAIL'))->send(new \App\Mail\sendToAdEmail($this->ad));
        Mail::to($this->ad->email)->send(new \App\Mail\sendToAdEmail($this->ad));
    }
}
