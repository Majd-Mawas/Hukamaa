<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ArabicNumeric implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Convert Arabic numerals to standard numerals
        $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $standardNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $convertedValue = str_replace($arabicNumerals, $standardNumerals, (string) $value);

        // Check if the converted value is numeric
        if (!is_numeric($convertedValue)) {
            $fail('The :attribute must be a number.');
        }
    }
}
