<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceReminder extends Notification
{
    use Queueable;

    public function __construct(
        public Invoice $invoice,
        public int $daysOffset
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $invoice = $this->invoice;
        $days = $this->daysOffset;

        $subject = match (true) {
            $days > 0 => "Invoice Reminder: {$invoice->invoice_number} due in {$days} day(s)",
            $days === 0 => "Invoice Due Today: {$invoice->invoice_number}",
            default => "Invoice Overdue: {$invoice->invoice_number} ({$days} days overdue)",
        };

        $message = match (true) {
            $days > 0 => "Your invoice **{$invoice->invoice_number}** is due in **{$days} day(s)**.",
            $days === 0 => "Your invoice **{$invoice->invoice_number}** is **due today**.",
            default => "Your invoice **{$invoice->invoice_number}** is **overdue by " . abs($days) . " day(s)**.",
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($message)
            ->line('Amount: ' . number_format($invoice->total, 2) . ' ' . $invoice->currency)
            ->line('Due Date: ' . $invoice->due_date->format('d M Y'))
            ->action('View & Pay Invoice', url('/customer/invoices/' . $invoice->id))
            ->line('Thank you for your business!');
    }
}