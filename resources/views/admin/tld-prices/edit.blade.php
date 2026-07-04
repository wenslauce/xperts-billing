<?php

namespace App\Notifications;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainExpiring extends Notification
{
    use Queueable;

    public function __construct(public Domain $domain, public int $daysRemaining) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Domain Expiry Reminder: {$this->domain->name} expires in {$this->daysRemaining} day(s)")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("Your domain **{$this->domain->name}** is expiring in **{$this->daysRemaining} day(s)**.")
            ->line('Expiry Date: ' . $this->domain->expiry_date->format('d M Y'))
            ->line('Renew your domain to avoid service interruption.')
            ->action('Renew Domain', url('/customer/domains'))
            ->line('Thank you for choosing us!');
    }
}