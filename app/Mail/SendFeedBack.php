<?php

namespace App\Mail;

use App\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendFeedBack extends Mailable
{
    use Queueable, SerializesModels;
    public $feedBack;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedBack)
    {
        //
        $this->feedBack = $feedBack;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('嘿市网用户意见反馈通知')->view('email.sendFeedBack');
    }
}
