<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WasteReport;
use App\Mail\CollectorAssignmentMail;

class ReportAssignedToCollector extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(WasteReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new CollectorAssignmentMail($this->report, $notifiable))
                    ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'report_assigned',
            'report_id' => $this->report->id,
            'reference' => $this->report->reference_code ?? 'Report #' . $this->report->id,
            'waste_type' => $this->report->waste_type,
            'location' => $this->report->location,
            'urgency' => $this->report->urgency ?? 'normal',
            'resident_name' => $this->report->resident->name ?? 'Unknown',
            'assigned_at' => now()->toISOString(),
            'url' => route('collector.report.show', $this->report->id),
            'message' => 'New waste collection report assigned to you: ' . ($this->report->reference_code ?? 'Report #' . $this->report->id)
        ];
    }
}
