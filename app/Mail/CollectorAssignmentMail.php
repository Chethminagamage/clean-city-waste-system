<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\WasteReport;

class CollectorAssignmentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $report;
    public $collector;

    /**
     * Create a new message instance.
     */
    public function __construct(WasteReport $report, $collector)
    {
        $this->report = $report;
        $this->collector = $collector;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🗑️ New Waste Collection Assignment - ' . ($this->report->reference_code ?? 'Report #' . $this->report->id),
            from: new \Illuminate\Mail\Mailables\Address(
                config('mail.from.address'), 
                config('mail.from.name')
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.collector-assignment',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
