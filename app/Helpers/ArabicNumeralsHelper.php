<?php

namespace App\Helpers;

class ArabicNumeralsHelper
{
    /**
     * Convert Arabic numerals to standard numerals
     *
     * @param string|mixed $value
     * @return string
     */
    public static function convertToStandardNumerals($value): string
    {
        // Convert Arabic numerals to standard numerals
        $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $standardNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($arabicNumerals, $standardNumerals, (string) $value);
    }
}
