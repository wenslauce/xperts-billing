<?php

namespace App\Notifications;

use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProvisioningComplete extends Notification
{
    use Queueable;

    public function __construct(
        public Service $service,
        public string $username,
        public string $password
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Hosting Account is Ready!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your hosting account has been provisioned successfully.')
            ->line('Here are your login details:')
            ->line('Control Panel: https://' . ($this->service->server->hostname ?? 'your-server') . ':2222')
            ->line('Username: ' . $this->username)
            ->line('Password: ' . $this->password)
            ->line('Domain: ' . ($this->service->domain ?? 'N/A'))
            ->action('Login to Control Panel', 'https://' . ($this->service->server->hostname ?? '') . ':2222')
            ->line('Please change your password after first login.')
            ->line('Thank you for choosing us!');
    }
}