<?php

namespace App\Notifications;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProvisioningFailed extends Notification
{
    use Queueable;

    public function __construct(
        public Service $service,
        public string $error
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Hosting Provisioning Failed - ' . ($this->service->domain ?? 'N/A'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We encountered an error while setting up your hosting account.')
            ->line('Service: ' . ($this->service->product->name ?? 'N/A'))
            ->line('Domain: ' . ($this->service->domain ?? 'N/A'))
            ->line('Error: ' . $this->error)
            ->line('Our support team has been notified and will resolve this shortly.')
            ->action('Contact Support', url('/customer/tickets'))
            ->line('We apologize for the inconvenience.');
    }
}