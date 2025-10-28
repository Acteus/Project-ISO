<?php

namespace App\Mail;

use App\Models\WeeklyMetric;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyProgressReport extends Mailable
{
    use Queueable, SerializesModels;

    public $weeklyMetric;
    public $previousMetric;

    /**
     * Create a new message instance.
     */
    public function __construct(WeeklyMetric $weeklyMetric, WeeklyMetric $previousMetric = null)
    {
        $this->weeklyMetric = $weeklyMetric;
        $this->previousMetric = $previousMetric;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ISO 21001 Weekly Progress Report - ' . $this->weeklyMetric->date_range_label,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-progress-report',
            with: [
                'weeklyMetric' => $this->weeklyMetric,
                'previousMetric' => $this->previousMetric,
                'comparison' => $this->generateComparison(),
            ],
        );
    }

    /**
     * Generate comparison data for the email
     */
    private function generateComparison()
    {
        if (!$this->previousMetric) {
            return null;
        }

        return [
            'satisfaction_change' => round($this->weeklyMetric->overall_satisfaction - $this->previousMetric->overall_satisfaction, 2),
            'compliance_change' => round($this->weeklyMetric->compliance_score - $this->previousMetric->compliance_score, 2),
            'response_change' => $this->weeklyMetric->new_responses - $this->previousMetric->new_responses,
            'satisfaction_trend' => $this->weeklyMetric->overall_satisfaction > $this->previousMetric->overall_satisfaction ? 'up' :
                                   ($this->weeklyMetric->overall_satisfaction < $this->previousMetric->overall_satisfaction ? 'down' : 'stable'),
            'compliance_trend' => $this->weeklyMetric->compliance_score > $this->previousMetric->compliance_score ? 'up' :
                                 ($this->weeklyMetric->compliance_score < $this->previousMetric->compliance_score ? 'down' : 'stable'),
            'response_trend' => $this->weeklyMetric->new_responses > $this->previousMetric->new_responses ? 'up' :
                               ($this->weeklyMetric->new_responses < $this->previousMetric->new_responses ? 'down' : 'stable'),
        ];
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
