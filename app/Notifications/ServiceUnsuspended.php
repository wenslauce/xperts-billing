<?php

namespace App\Notifications;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceUnsuspended extends Notification
{
    use Queueable;

    public function __construct(
        public Service $service
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Service Restored: ' . ($this->service->domain ?? 'Service #' . $this->service->id))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your service **' . ($this->service->domain ?? 'Service #' . $this->service->id) . '** has been **restored** after successful payment.')
            ->line('Your service is now active and fully functional.')
            ->action('View Service Details', url('/customer/services/' . $this->service->id))
            ->line('Thank you for your business!');
    }
}