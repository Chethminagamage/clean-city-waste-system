<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Mail\ReportUpdateMail;

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

    public function toMail($notifiable)
    {
        return (new ReportUpdateMail($this->report, $notifiable, $this->reason))
                    ->to($notifiable->email);
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