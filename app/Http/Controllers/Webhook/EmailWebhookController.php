<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use App\Notifications\NewTicket;
use App\Notifications\TicketReplied;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class EmailWebhookController extends Controller
{
    public function handle(Request $request): \Illuminate\Http\JsonResponse
    {
        $provider = $this->detectProvider($request);
        if (! $this->verifySignature($request, $provider)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }
        $emailData = $this->parseEmail($request, $provider);
        if (! $emailData) return response()->json(['error' => 'Failed to parse email'], 400);

        $from = $emailData['from'];
        $subject = $emailData['subject'];
        $body = $emailData['body'];
        $to = $emailData['to'] ?? '';
        $department = TicketDepartment::where('email', $to)->first();
        $ticketId = $this->extractTicketId($subject);

        if ($ticketId) $this->addReplyToTicket($ticketId, $from, $body);
        else $this->createTicketFromEmail($from, $subject, $body, $department);

        return response()->json(['status' => 'ok']);
    }

    protected function detectProvider(Request $request): string
    {
        if ($request->has('event') && $request->has('email')) return 'sendgrid';
        if ($request->has('event-data') && $request->has('signature')) return 'mailgun';
        if ($request->has('FromName') && $request->has('From')) return 'postmark';
        return 'generic';
    }

    protected function verifySignature(Request $request, string $provider): bool
    {
        $secret = Setting::getValue('email_piping_webhook_secret', '');
        if (empty($secret)) return true;
        return match ($provider) {
            'sendgrid' => $this->verifySendGrid($request, $secret),
            'mailgun' => $this->verifyMailgun($request, $secret),
            'postmark' => $this->verifyPostmark($request, $secret),
            default => true,
        };
    }

    protected function verifySendGrid(Request $request, string $secret): bool
    {
        $sig = $request->header('X-Twilio-Email-Webhook-Signature');
        $ts = $request->header('X-Twilio-Email-Webhook-Timestamp');
        if (! $sig || ! $ts) return false;
        return hash_equals(base64_encode(hash_hmac('sha256', $ts . $request->getContent(), $secret, true)), $sig);
    }

    protected function verifyMailgun(Request $request, string $secret): bool
    {
        $s = $request->input('signature');
        if (! $s) return false;
        return hash_equals(hash_hmac('sha256', ($s['timestamp'] ?? '') . ($s['token'] ?? ''), $secret), $s['signature'] ?? '');
    }

    protected function verifyPostmark(Request $request, string $secret): bool
    {
        $sig = $request->header('X-Postmark-Signature');
        if (! $sig) return false;
        return hash_equals(base64_encode(hash_hmac('sha256', $request->getContent(), $secret, true)), $sig);
    }

    protected function parseEmail(Request $request, string $provider): ?array
    {
        return match ($provider) {
            'sendgrid' => $this->parseSendGrid($request),
            'mailgun' => $this->parseMailgun($request),
            'postmark' => $this->parsePostmark($request),
            default => $this->parseGeneric($request),
        };
    }

    protected function parseSendGrid(Request $request): array
    {
        $d = $request->all();
        return ['from' => $d['from'] ?? '', 'to' => $d['to'] ?? '', 'subject' => $d['subject'] ?? 'No Subject', 'body' => strip_tags($d['text'] ?? $d['html'] ?? ''), 'attachments' => []];
    }

    protected function parseMailgun(Request $request): array
    {
        $d = $request->all();
        return ['from' => $d['from'] ?? '', 'to' => $d['recipient'] ?? '', 'subject' => $d['subject'] ?? 'No Subject', 'body' => strip_tags($d['body-plain'] ?? $d['body-html'] ?? ''), 'attachments' => []];
    }

    protected function parsePostmark(Request $request): array
    {
        $d = $request->all();
        return ['from' => $d['From'] ?? '', 'to' => $d['To'] ?? '', 'subject' => $d['Subject'] ?? 'No Subject', 'body' => strip_tags($d['TextBody'] ?? $d['HtmlBody'] ?? ''), 'attachments' => []];
    }

    protected function parseGeneric(Request $request): array
    {
        return ['from' => $request->input('from', $request->input('sender', '')), 'to' => $request->input('to', $request->input('recipient', '')), 'subject' => $request->input('subject', 'No Subject'), 'body' => strip_tags($request->input('text', $request->input('body', $request->input('html', '')))), 'attachments' => []];
    }

    protected function extractTicketId(string $subject): ?int
    {
        if (preg_match('/\[Ticket\s*#(\d+)\]/i', $subject, $matches)) return (int) $matches[1];
        return null;
    }

    protected function addReplyToTicket(int $ticketId, string $from, string $body): void
    {
        $ticket = Ticket::find($ticketId);
        if (! $ticket) { Log::warning("Email webhook: Ticket #{$ticketId} not found"); return; }
        $ticket->replies()->create(['user_id' => $ticket->customer->user_id, 'message' => "From: {$from}\n\n{$body}", 'is_staff' => false]);
        $ticket->update(['status' => 'open', 'last_reply_at' => now()]);
        Notification::send(\App\Models\User::role(['super-admin', 'admin', 'support'])->get(), new TicketReplied($ticket));
        Log::info("Email webhook: Replied ticket #{$ticketId} from {$from}");
    }

    protected function createTicketFromEmail(string $from, string $subject, string $body, ?TicketDepartment $department): void
    {
        $user = \App\Models\User::where('email', $from)->first();
        if (! $user || ! $user->customer) { Log::warning("Email webhook: No customer for {$from}"); return; }
        $ticket = Ticket::create(['customer_id' => $user->customer->id, 'department_id' => $department?->id, 'subject' => $subject, 'status' => 'open', 'priority' => 'medium']);
        $ticket->replies()->create(['user_id' => $user->id, 'message' => "From: {$from}\n\n{$body}", 'is_staff' => false]);
        Notification::send(\App\Models\User::role(['super-admin', 'admin', 'support'])->get(), new NewTicket($ticket));
        Log::info("Email webhook: Created ticket #{$ticket->id} from {$from}");
    }
}