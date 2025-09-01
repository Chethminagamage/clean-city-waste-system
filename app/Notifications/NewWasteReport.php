<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WasteReport;

class NewWasteReport extends Notification implements ShouldQueue
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
        return ['database']; // Only send to database, not email to prevent spam
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Waste Report Submitted')
            ->greeting('New Waste Report')
            ->line("A new waste report has been submitted by a resident:")
            ->line("**Report ID:** {$this->report->reference_code}")
            ->line("**Location:** {$this->report->location}")
            ->line("**Waste Type:** {$this->report->waste_type}")
            ->action('View Report Details', route('admin.reports.show', $this->report->id))
            ->line('Please review and assign a collector when appropriate.')
            ->salutation('Clean City Waste Management System');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_waste_report',
            'report_id' => $this->report->id,
            'reference_code' => $this->report->reference_code,
            'location' => $this->report->location,
            'waste_type' => $this->report->waste_type,
            'status' => $this->report->status,
            'title' => '📋 New Waste Report Submitted',
            'message' => "New {$this->report->waste_type} waste reported at {$this->report->location}",
        ];
    }
}
