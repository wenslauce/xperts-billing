<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceSuspended extends Notification
{
    use Queueable;

    public function __construct(
        public Service $service,
        public Invoice $invoice
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Service Suspended: ' . ($this->service->domain ?? 'Service #' . $this->service->id))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your service **' . ($this->service->domain ?? 'Service #' . $this->service->id) . '** has been **suspended** due to unpaid invoice **' . $this->invoice->invoice_number . '**.')
            ->line('Invoice Amount: ' . number_format($this->invoice->total, 2) . ' ' . $this->invoice->currency)
            ->line('Due Date: ' . $this->invoice->due_date->format('d M Y'))
            ->action('Pay Invoice & Restore Service', url('/customer/invoices/' . $this->invoice->id))
            ->line('Once payment is received, your service will be automatically restored.')
            ->line('Thank you for your business!');
    }
}