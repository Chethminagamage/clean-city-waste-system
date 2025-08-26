<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Feedback;

class FeedbackResponseReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $feedback;

    /**
     * Create a new notification instance.
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
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
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Response to Your Feedback - Clean City')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('We have responded to your recent feedback.')
                    ->line('**Your Feedback:** ' . ($this->feedback->message ?: 'Rating: ' . $this->feedback->rating . ' stars'))
                    ->line('**Our Response:** ' . $this->feedback->admin_response)
                    ->action('View Full Response', url('/resident/feedback/' . $this->feedback->id))
                    ->line('Thank you for helping us improve our waste management services!')
                    ->line('Best regards,')
                    ->line('Clean City Team');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'feedback_response',
            'feedback_id' => $this->feedback->id,
            'title' => 'Response to Your Feedback',
            'message' => 'We have responded to your feedback about ' . ucfirst(str_replace('_', ' ', $this->feedback->feedback_type)),
            'admin_response' => $this->feedback->admin_response,
            'feedback_type' => $this->feedback->feedback_type,
            'rating' => $this->feedback->rating,
            'responded_at' => $this->feedback->admin_responded_at,
            'action_url' => url('/resident/feedback/' . $this->feedback->id),
            'action_text' => 'View Response'
        ];
    }
}
