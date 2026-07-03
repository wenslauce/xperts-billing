<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewTicket extends Notification
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
            ->subject('New Support Ticket: ' . $this->ticket->subject)
            ->greeting('New Ticket from ' . ($this->ticket->customer->user->name ?? 'Customer'))
            ->line('Subject: ' . $this->ticket->subject)
            ->line('Priority: ' . ucfirst($this->ticket->priority))
            ->line('Department: ' . ($this->ticket->department->name ?? 'General'))
            ->action('View Ticket', url('/admin/tickets/' . $this->ticket->id));
    }
}