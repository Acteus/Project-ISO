<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class MonthlyComplianceReport extends Mailable
{
    use Queueable, SerializesModels;

    public $reportData;
    public $pdfPath;

    /**
     * Create a new message instance.
     */
    public function __construct($reportData, $pdfPath = null)
    {
        $this->reportData = $reportData;
        $this->pdfPath = $pdfPath;
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

        if ($this->pdfPath && \Illuminate\Support\Facades\Storage::exists($this->pdfPath)) {
            $attachments[] = Attachment::fromStorage($this->pdfPath)
                ->as('monthly-compliance-report-' . $this->reportData['year'] . '-' . str_pad($this->reportData['month'], 2, '0', STR_PAD_LEFT) . '.html')
                ->withMime('text/html');
        }

        return $attachments;
    }
}
