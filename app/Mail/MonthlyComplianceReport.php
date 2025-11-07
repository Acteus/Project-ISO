<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class MonthlyComplianceReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reportData;
    public $reportHtml;

    /**
     * Create a new message instance.
     */
    public function __construct($reportData, $reportHtml = null)
    {
        $this->reportData = $reportData;
        $this->reportHtml = $reportHtml;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Monthly ISO 21001 Compliance Report - ' . $this->reportData['month'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.monthly-compliance-report',
            with: [
                'reportData' => $this->reportData,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->reportHtml) {
            $year = (string) ($this->reportData['year'] ?? now()->format('Y'));
            $monthValue = $this->reportData['month'] ?? now()->format('m');
            $monthSlug = is_numeric($monthValue)
                ? str_pad((string) $monthValue, 2, '0', STR_PAD_LEFT)
                : Str::slug((string) $monthValue, '-');

            $attachments[] = Attachment::fromData(function () {
                return $this->reportHtml;
            }, 'monthly-compliance-report-' . $year . '-' . $monthSlug . '.html')
                ->withMime('text/html');
        }

        return $attachments;
    }
}
