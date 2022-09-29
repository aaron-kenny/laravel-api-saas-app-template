<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BrokerResponse extends Notification implements ShouldQueue
{
    use Queueable;

    public $broker;
    public $brokerResponse;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($broker, $brokerResponse)
    {
        $this->broker = $broker;
        $this->brokerResponse = $brokerResponse;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if($notifiable->settings()->get('product_two.notifications.brokerResponse.email')) {
            return ['mail'];
        }
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
                    ->subject('Response from ' . $this->broker)
                    ->replyTo('support@laravelwebsite.com', 'Laravel Website Support')
                    ->markdown('mail.broker-response', [
                        'firstName' => $notifiable->first_name,
                        'broker' => $this->broker,
                        'brokerResponse' => $this->brokerResponse
                    ]);
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