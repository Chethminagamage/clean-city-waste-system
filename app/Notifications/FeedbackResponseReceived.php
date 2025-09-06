<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        return ['database'];
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
