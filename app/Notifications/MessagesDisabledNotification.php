<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

class MessagesDisabledNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private string $trigger, private string $reason, private bool $error = false)
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
        return ['database', "broadcast"];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    #[ArrayShape(["trigger" => "string", "reason" => "string", "error" => "bool", "time" => "float|int|string"])]
    public function toArray($notifiable): array
    {
        return [
            "trigger" => $this->trigger,
            "reason" => $this->reason,
            "error" => $this->error,
            "time" => now()->timestamp
        ];
    }
}
