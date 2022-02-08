<?php

namespace App\Modules\Order\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateOrderStatus extends Notification
{
    use Queueable;

    private $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->orderData = $data;
        $this->data['type'] = 'order';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', 'https://laravel.com')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

            'title' => [
                'en' => 'Order Status updated',
                'ar' => ' تغير حالة الطلب'
            ],
            'body' => [
                'en' => 'GeneralManager updated status order  No ' . $this->orderData['unique_id'] . ' to  ' . $this->orderData['status'],
                'ar' => 'تم تغيير حاله الطلب رقم ' . $this->orderData['unique_id'] . ' من  قبل الادمن الى  ' . $this->orderData['status']
            ]
        ];
    }
}
