<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UserLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new messages instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info('223');
    }

    /**
     * Build the messages.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('Works');
        return $this->view('emails.loginEmail');
    }
}
