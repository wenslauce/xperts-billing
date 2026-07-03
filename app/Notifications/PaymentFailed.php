<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentFailed extends Notification
{
    use Queueable;

    public function __construct(
        public Invoice $invoice,
        public string $gateway,
        public ?string $message = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Failed - ' . $this->invoice->invoice_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your payment for invoice ' . $this->invoice->invoice_number . ' has failed.')
            ->line('Amount: ' . number_format($this->invoice->total, 2) . ' ' . $this->invoice->currency)
            ->line('Gateway: ' . ucfirst($this->gateway))
            ->lineIf($this->message, 'Reason: ' . $this->message)
            ->action('Try Again', url('/customer/invoices/' . $this->invoice->id))
            ->line('Please try again or contact support if you need assistance.');
    }
}