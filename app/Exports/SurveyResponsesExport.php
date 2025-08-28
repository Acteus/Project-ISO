<?php

namespace App\Exports;

use App\Models\SurveyResponse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyResponsesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $program;
    protected $yearLevel;

    public function __construct($program = null, $yearLevel = null)
    {
        $this->program = $program;
        $this->yearLevel = $yearLevel;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = SurveyResponse::query();

        if ($this->program) {
            $query->where('program', $this->program);
        }

        if ($this->yearLevel) {
            $query->where('year_level', $this->yearLevel);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Student Number (Anonymized)',
            'Program',
            'Year Level',
            'Course Content Rating',
            'Facilities Rating',
            'Support Services Rating',
            'Overall Satisfaction',
            'Consent Given',
            'Submitted At'
        ];
    }

    public function map($response): array
    {
        return [
            $response->anonymous_id, // Use anonymous ID instead of actual student number
            $response->program,
            $response->year_level,
            $response->course_content_rating,
            $response->facilities_rating,
            $response->support_services_rating,
            $response->overall_satisfaction,
            $response->consent_given ? 'Yes' : 'No',
            $response->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
