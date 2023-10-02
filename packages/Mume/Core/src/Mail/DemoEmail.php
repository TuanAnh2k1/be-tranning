<?php

namespace Mume\CoreV2\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DemoEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Mail::send([], [], function ($message) {
            $message->to('test@gmail.com')
                ->subject("Subject")
            // here comes what you want
                ->setBody('Hi, welcome user!') // assuming text/plain
            // or:
                ->setBody('<h1>Hi, welcome user!</h1>', 'text/html'); // for HTML rich messages
        });
    }
}
