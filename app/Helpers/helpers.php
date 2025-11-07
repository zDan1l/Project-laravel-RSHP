<?php

use App\Helpers\StringHelper;
use App\Helpers\ValidationHelper;

if (!function_exists('format_nama')) {
    /**
     * Format nama dengan kapitalisasi
     * 
     * @param string $text
     * @return string
     */
    function format_nama($text)
    {
        return StringHelper::formatName($text);
    }
}

if (!function_exists('format_nama_jenis_hewan')) {
    /**
     * Format nama jenis hewan
     * 
     * @param string $namaJenis
     * @return string
     */
    function format_nama_jenis_hewan($namaJenis)
    {
        return StringHelper::formatNamaJenisHewan($namaJenis);
    }
}

if (!function_exists('sanitize_text')) {
    /**
     * Sanitize text
     * 
     * @param string $text
     * @return string
     */
    function sanitize_text($text)
    {
        return StringHelper::sanitize($text);
    }
}

if (!function_exists('slugify')) {
    /**
     * Generate slug
     * 
     * @param string $text
     * @return string
     */
    function slugify($text)
    {
        return StringHelper::slugify($text);
    }
}

if (!function_exists('validate_nama_jenis_hewan')) {
    /**
     * Validate nama jenis hewan
     * 
     * @param string $namaJenis
     * @return array
     */
    function validate_nama_jenis_hewan($namaJenis)
    {
        return ValidationHelper::validateNamaJenisHewan($namaJenis);
    }
}
