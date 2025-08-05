<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetUrl;
    public $name;

    public function __construct($resetUrl, $name)
    {
        $this->resetUrl = $resetUrl;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Reset Your Clean City Password')
                    ->view('emails.password_reset')
                    ->with([
                        'resetUrl' => $this->resetUrl,
                        'name' => $this->name,
                    ]);
    }
}
