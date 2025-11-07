<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * Data Minimization Service
 * 
 * Ensures only essential ISO 21001 metrics are collected (GDPR & ISO 27001 compliant)
 * 
 * @package App\Services
 */
class DataMinimizationService
{
    /**
     * Validate that only allowed fields are present in data
     * 
     * @param array $data The data to validate
     * @return array Array with 'valid' => bool and 'rejected_fields' => array
     */
    public function validateDataMinimization(array $data): array
    {
        if (!Config::get('privacy.data_minimization.enabled', true)) {
            return ['valid' => true, 'rejected_fields' => []];
        }

        $allowedFields = Config::get('privacy.data_minimization.allowed_fields', []);
        $rejectedFields = [];

        foreach ($data as $field => $value) {
            // Skip null/empty values
            if ($value === null || $value === '') {
                continue;
            }

            // Check if field is allowed
            if (!in_array($field, $allowedFields)) {
                $rejectedFields[] = $field;
                Log::warning('Data minimization violation', [
                    'field' => $field,
                    'value_preview' => is_string($value) ? substr($value, 0, 50) : 'non-string',
                ]);
            }
        }

        return [
            'valid' => empty($rejectedFields),
            'rejected_fields' => $rejectedFields,
        ];
    }

    /**
     * Filter data to only include allowed fields
     * 
     * @param array $data The data to filter
     * @return array Filtered data with only allowed fields
     */
    public function filterAllowedFields(array $data): array
    {
        if (!Config::get('privacy.data_minimization.enabled', true)) {
            return $data;
        }

        $allowedFields = Config::get('privacy.data_minimization.allowed_fields', []);
        $filtered = [];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $filtered[$field] = $data[$field];
            }
        }

        return $filtered;
    }

    /**
     * Get list of allowed fields for data collection
     * 
     * @return array
     */
    public function getAllowedFields(): array
    {
        return Config::get('privacy.data_minimization.allowed_fields', []);
    }
}


