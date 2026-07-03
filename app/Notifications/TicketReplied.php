<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketReplied extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Reply: ' . $this->ticket->subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your support ticket has received a new reply.')
            ->line('Subject: ' . $this->ticket->subject)
            ->action('View Ticket', url('/customer/tickets/' . $this->ticket->id));
    }
}