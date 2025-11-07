<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * Encryption Service
 * 
 * Provides AES-256 encryption for sensitive student data (GDPR & ISO 27001 compliant)
 * 
 * @package App\Services
 */
class EncryptionService
{
    /**
     * Encrypt sensitive data using AES-256-CBC
     * 
     * @param string|null $value The value to encrypt
     * @return string|null Encrypted value or null if input is empty
     */
    public function encrypt(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Laravel's Crypt uses AES-256-CBC by default
            // This is configured in config/app.php with cipher => 'AES-256-CBC'
            return Crypt::encryptString($value);
        } catch (\Exception $e) {
            Log::error('Encryption failed', [
                'error' => $e->getMessage(),
                'field' => 'encrypted_field',
            ]);
            throw new \RuntimeException('Failed to encrypt sensitive data: ' . $e->getMessage());
        }
    }

    /**
     * Decrypt sensitive data
     * 
     * @param string|null $encryptedValue The encrypted value to decrypt
     * @return string|null Decrypted value or null if input is empty
     */
    public function decrypt(?string $encryptedValue): ?string
    {
        if (empty($encryptedValue)) {
            return null;
        }

        try {
            return Crypt::decryptString($encryptedValue);
        } catch (\Exception $e) {
            Log::error('Decryption failed', [
                'error' => $e->getMessage(),
                'field' => 'encrypted_field',
            ]);
            // Return null instead of throwing to prevent breaking the application
            // Log the error for investigation
            return null;
        }
    }

    /**
     * Check if a field should be encrypted based on configuration
     * 
     * @param string $fieldName The field name to check
     * @return bool True if field should be encrypted
     */
    public function shouldEncrypt(string $fieldName): bool
    {
        $encryptedFields = Config::get('privacy.encryption.encrypted_fields', []);
        return in_array($fieldName, $encryptedFields);
    }

    /**
     * Encrypt multiple fields at once
     * 
     * @param array $data Array of field => value pairs
     * @return array Array with encrypted values
     */
    public function encryptFields(array $data): array
    {
        $encrypted = [];
        foreach ($data as $field => $value) {
            if ($this->shouldEncrypt($field) && !empty($value)) {
                $encrypted[$field] = $this->encrypt($value);
            } else {
                $encrypted[$field] = $value;
            }
        }
        return $encrypted;
    }
}

