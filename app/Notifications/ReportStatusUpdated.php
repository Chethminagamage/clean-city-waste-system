<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReportStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public \App\Models\WasteReport $report,
        public string $reason = '' // 'assigned' | 'scheduled' | 'collected' | 'closed' | 'cancelled'
    ) {}

    /** Send via email + database */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
{
    $r = $this->report;

    return (new MailMessage)
        ->subject('Report Update: '.($r->reference_code ?? ('Report #'.$r->id)))
        ->markdown('emails.report-update', [
            'report' => $r,
            'reason' => $this->reason,                     // 'assigned' | 'scheduled' | 'collected' | 'closed' | 'cancelled'
            'url'    => route('resident.reports.show', $r->id),
            'name'   => $notifiable->name,
        ]);
}

    /** Payload saved to notifications table */
    public function toDatabase($notifiable): array
    {
        $r = $this->report;

        return [
            'report_id'   => $r->id,
            'reference'   => $r->reference_code ?? ('#'.$r->id),
            'status'      => strtolower((string)$r->status),
            'reason'      => $this->reason,
            'waste_type'  => $r->waste_type,
            'location'    => $r->location,
            'url'         => route('resident.reports.show', $r->id),
        ];
    }
}