<?php

namespace App\Notifications;

use App\Models\User;
use Cog\Laravel\Ban\Models\Ban;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BanNotification extends Notification
{
    use Queueable;
    private User $user;
    private Ban $ban;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$ban)
    {
        $this->user = $user;
        $this->ban = $ban;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
