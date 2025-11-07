<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * Validasi nama jenis hewan
     * 
     * @param string $namaJenis
     * @return array ['valid' => bool, 'message' => string]
     */
    public static function validateNamaJenisHewan($namaJenis)
    {
        // Check if empty
        if (empty(trim($namaJenis))) {
            return [
                'valid' => false,
                'message' => 'Nama jenis hewan tidak boleh kosong'
            ];
        }

        // Check length
        if (strlen($namaJenis) > 100) {
            return [
                'valid' => false,
                'message' => 'Nama jenis hewan maksimal 100 karakter'
            ];
        }

        // Check if contains only letters and spaces
        if (!preg_match('/^[a-zA-Z\s]+$/', $namaJenis)) {
            return [
                'valid' => false,
                'message' => 'Nama jenis hewan hanya boleh berisi huruf dan spasi'
            ];
        }

        return [
            'valid' => true,
            'message' => 'Valid'
        ];
    }

    /**
     * Sanitize input data
     * 
     * @param array $data
     * @return array
     */
    public static function sanitizeInput($data)
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = trim(strip_tags($value));
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}
