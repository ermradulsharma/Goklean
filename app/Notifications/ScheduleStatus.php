<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleStatus extends Notification
{
    use Queueable;

    private $schedule;

    /**
     * Create a new notification instance.
     *
     * @param $schedule
     */
    public function __construct($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("Schedule {$this->schedule['code']} Status Update")
                    ->greeting("Hello {$this->schedule['customer']}")
                    ->line("You car wash scheduled for {$this->schedule['customer_car']} on {$this->schedule['date']} has been {$this->schedule['status']}.")
                    ->line("Schedule ID: {$this->schedule['code']}");
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
