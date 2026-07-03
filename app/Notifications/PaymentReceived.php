<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification
{
    use Queueable;

    public function __construct(
        public Invoice $invoice,
        public Transaction $transaction
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Received - ' . $this->invoice->invoice_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We have received your payment of ' . number_format($this->transaction->amount, 2) . ' ' . $this->transaction->currency . '.')
            ->line('Invoice: ' . $this->invoice->invoice_number)
            ->line('Payment Method: ' . ucfirst($this->transaction->gateway))
            ->line('Reference: ' . $this->transaction->gateway_reference)
            ->action('View Invoice', url('/customer/invoices/' . $this->invoice->id))
            ->line('Thank you for your business!');
    }
}