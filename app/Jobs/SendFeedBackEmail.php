<?php

namespace App\Jobs;

use App\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendFeedBackEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $feedBack;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedBack)
    {
        $this->feedBack = $feedBack;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Mail::to(env('PLUG_CHECK_EMAIL'))->send(new \App\Mail\SendFeedBack($this->feedBack));
    }
}
