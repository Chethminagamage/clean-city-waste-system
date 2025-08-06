<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // ✅ Declare the property

    public function __construct($user) // ✅ Receive it from controller
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Your Clean City Password Has Been Changed')
                    ->view('emails.password_changed')
                    ->with([
                        'user' => $this->user // ✅ Now it's defined
                    ]);
    }
}