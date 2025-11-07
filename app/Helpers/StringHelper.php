<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Format nama dengan kapitalisasi setiap kata
     * 
     * @param string $text
     * @return string
     */
    public static function formatName($text)
    {
        // Trim whitespace
        $text = trim($text);
        
        // Convert to lowercase first
        $text = strtolower($text);
        
        // Capitalize first letter of each word
        $text = ucwords($text);
        
        return $text;
    }

    /**
     * Format nama jenis hewan dengan aturan khusus
     * Contoh: "anjing" -> "Anjing", "kucing persia" -> "Kucing Persia"
     * 
     * @param string $namaJenis
     * @return string
     */
    public static function formatNamaJenisHewan($namaJenis)
    {
        return self::formatName($namaJenis);
    }

    /**
     * Sanitize string untuk mencegah XSS
     * 
     * @param string $text
     * @return string
     */
    public static function sanitize($text)
    {
        return htmlspecialchars(strip_tags($text), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate slug dari text
     * 
     * @param string $text
     * @return string
     */
    public static function slugify($text)
    {
        // Replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        
        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        
        // Trim
        $text = trim($text, '-');
        
        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        
        // Lowercase
        $text = strtolower($text);
        
        if (empty($text)) {
            return 'n-a';
        }
        
        return $text;
    }

    /**
     * Validasi apakah string hanya berisi huruf dan spasi
     * 
     * @param string $text
     * @return bool
     */
    public static function isAlphaSpace($text)
    {
        return preg_match('/^[a-zA-Z\s]+$/', $text) === 1;
    }

    /**
     * Limit text dengan ellipsis
     * 
     * @param string $text
     * @param int $limit
     * @param string $end
     * @return string
     */
    public static function limit($text, $limit = 100, $end = '...')
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }
        
        return mb_substr($text, 0, $limit) . $end;
    }
}
