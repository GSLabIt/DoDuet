<?php

namespace App\Notifications;

use App\Models\Mentions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

class UserMentionedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private Mentions $mention)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    #[ArrayShape(["type" => "string", "mention_id" => "string"])] public function toArray($notifiable): array
    {
        return [
            "type" => "mention",
            "mention_id" => $this->mention->id,
        ];
    }
}
