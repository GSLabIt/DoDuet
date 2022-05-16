<?php

namespace App\Notifications;

use App\Models\Reports;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportNotification extends Notification
{
    use Queueable;
    private User $user;
    private Reports $report;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$report)
    {
        $this->user = $user;
        $this->report = $report;
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
