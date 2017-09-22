<?php

namespace App\Mail;

use App\Plug;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendCheckPlug extends Mailable
{
    use Queueable, SerializesModels;

    public $plug;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Plug $plug)
    {
        //
        $this->plug = $plug;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('嘿市网插件等待审核')->view('email.sendMsg');
    }
}
