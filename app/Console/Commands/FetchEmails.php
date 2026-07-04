<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchEmails extends Command
{
    protected $signature = 'email-piping:fetch';
    protected $description = 'Fetch emails from IMAP server and process them as tickets';

    public function handle(): int
    {
        $this->info('Fetching emails from IMAP server...');

        $host = Setting::getValue('email_piping_imap_host');
        $port = Setting::getValue('email_piping_imap_port', 993);
        $username = Setting::getValue('email_piping_imap_username');
        $password = Setting::getValue('email_piping_imap_password');
        $folder = Setting::getValue('email_piping_imap_folder', 'INBOX');
        $encryption = Setting::getValue('email_piping_imap_encryption', 'ssl');

        if (! $host || ! $username || ! $password) {
            $this->error('IMAP settings not configured. Please configure email piping settings first.');
            return Command::FAILURE;
        }

        try {
            $connection = $this->connect($host, $port, $username, $password, $encryption);
            
            if (! $connection) {
                $this->error('Failed to connect to IMAP server');
                return Command::FAILURE;
            }

            $this->info("Connected to {$host}:{$port} ({$encryption})");

            $emails = $this->fetchUnreadEmails($connection, $folder);
            $processed = 0;

            foreach ($emails as $email) {
                if ($this->processEmail($email)) {
                    $processed++;
                }
            }

            imap_close($connection);

            $this->info("Processed {$processed} email(s)");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Email piping error: ' . $e->getMessage());
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function connect(string $host, int $port, string $username, string $password, string $encryption): ?\IMAP\Connection
    {
        $flags = '/imap';
        
        if ($encryption === 'ssl') {
            $flags .= '/ssl';
        } elseif ($encryption === 'tls') {
            $flags .= '/tls';
        }

        $flags .= '/novalidate-cert';

        $mailbox = "{$flags}{$host}:{$port}";

        try {
            return imap_open($mailbox, $username, $password);
        } catch (\Exception $e) {
            Log::error('IMAP connection failed: ' . $e->getMessage());
            return null;
        }
    }

    protected function fetchUnreadEmails(\IMAP\Connection $connection, string $folder): array
    {
        $emails = [];
        
        imap_reopen($connection, "{$folder}");
        
        $searchCriteria = 'UNSEEN';
        $messageNumbers = imap_search($connection, $searchCriteria);

        if (! $messageNumbers) {
            return $emails;
        }

        foreach ($messageNumbers as $msgNum) {
            $header = imap_headerinfo($connection, $msgNum);
            $body = imap_body($connection, $msgNum);
            $structure = imap_fetchstructure($connection, $msgNum);

            $emails[] = [
                'message_number' => $msgNum,
                'from' => $this->extractEmail($header->from[0] ?? null),
                'to' => $this->extractEmail($header->to[0] ?? null),
                'subject' => $header->subject ?? 'No Subject',
                'date' => $header->date ?? null,
                'body' => $body,
                'structure' => $structure,
            ];

            // Mark as read
            imap_setflag_full($connection, $msgNum, '\\Seen');
        }

        return $emails;
    }

    protected function extractEmail($address): string
    {
        if (! $address) {
            return '';
        }

        if (isset($address->mailbox) && isset($address->host)) {
            return "{$address->mailbox}@{$address->host}";
        }

        return (string) $address;
    }

    protected function processEmail(array $email): bool
    {
        try {
            // Forward to webhook controller for processing
            $request = new \Illuminate\Http\Request([
                'from' => $email['from'],
                'to' => $email['to'],
                'subject' => $email['subject'],
                'text' => $email['body'],
            ]);

            $controller = new \App\Http\Controllers\Webhook\EmailWebhookController();
            $response = $controller->handle($request);

            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            Log::error('Failed to process email: ' . $e->getMessage());
            return false;
        }
    }
}