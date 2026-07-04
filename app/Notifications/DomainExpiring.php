<?php

namespace App\Notifications;

use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainExpiring extends Notification
{
    use Queueable;

    public function __construct(
        public Domain $domain,
        public int $daysRemaining
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $domainName = $this->domain->name;
        $expiryDate = $this->domain->expiry_date->format('d M Y');

        return (new MailMessage)
            ->subject("Domain Expiry Reminder: {$domainName} expires in {$this->daysRemaining} day(s)")
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("Your domain **{$domainName}** is expiring in **{$this->daysRemaining} day(s)**.")
            ->line("Expiry Date: {$expiryDate}")
            ->line('Renew your domain to avoid service interruption and potential loss of the domain.')
            ->action('Renew Domain', url('/customer/domains'))
            ->line('Thank you for choosing us for your domain registration!');
    }
}