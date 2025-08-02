<?php

namespace App\Mail;

use App\Models\Collector;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollectorAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $email, $password;

public function __construct($name, $email, $password)
{
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
}

public function content(): Content
{
    return new Content(
        view: 'emails.collector_credentials',
    );
}
};