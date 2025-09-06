<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WasteReport;

class UrgentBinReport extends Notification implements ShouldQueue
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
        return ['database']; // Only in-app notifications, no emails
    }

    /**
     * Get the mail representation of the notification.
     * DISABLED: Only using database notifications for urgent bin reports
     */
    /*
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🚨 URGENT: Bin Full Report - Immediate Attention Required')
            ->greeting('Urgent Bin Collection Required!')
            ->line("A resident has reported their bin as full and needs urgent collection:")
            ->line("**Report ID:** {$this->report->reference_code}")
            ->line("**Location:** {$this->report->location}")
            ->line("**Waste Type:** {$this->report->waste_type}")
            ->line("**Status:** " . ucfirst($this->report->status))
            ->line("**Reported:** " . ($this->report->urgent_reported_at ? $this->report->urgent_reported_at->format('M d, Y h:i A') : 'Just now'))
            ->when($this->report->urgent_message, function ($mail) {
                return $mail->line("**Resident's Message:** \"{$this->report->urgent_message}\"");
            })
            ->action('View Report Details', route('admin.reports.show', $this->report->id))
            ->line('Please prioritize this collection to maintain service quality.')
            ->salutation('Clean City Waste Management System');
    }
    */

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'urgent_bin_report',
            'report_id' => $this->report->id,
            'reference_code' => $this->report->reference_code,
            'location' => $this->report->location,
            'waste_type' => $this->report->waste_type,
            'status' => $this->report->status,
            'urgent_message' => $this->report->urgent_message,
            'urgent_reported_at' => $this->report->urgent_reported_at,
            'title' => '🚨 Urgent Bin Collection Required',
            'message' => "Bin at {$this->report->location} is full and needs immediate attention",
        ];
    }
}
