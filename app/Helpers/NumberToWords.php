<?php

namespace App\Helpers;

class NumberToWords
{
    private static $ones = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
        'Seventeen', 'Eighteen', 'Nineteen'
    ];

    private static $tens = [
        '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
    ];

    public static function convert($number)
    {
        try {
            if ($number === null || $number === '') {
                return 'Zero Rupees Only';
            }
            
            if ($number == 0) {
                return 'Zero Rupees Only';
            }

            $number = (float) $number;
            
            if ($number > 999999999) {
                return 'Amount too large to convert';
            }
            
            $rupees = (int) $number;
            $paise = (int) round(($number - $rupees) * 100);

            $result = self::convertToWords($rupees) . ' Rupees';

            if ($paise > 0) {
                $result .= ' and ' . self::convertToWords($paise) . ' Paise';
            }

            return $result . ' Only';
        } catch (\Exception $e) {
            \Log::error('Error in NumberToWords::convert: ' . $e->getMessage(), [
                'number' => $number,
                'trace' => $e->getTraceAsString()
            ]);
            return 'Error converting amount';
        }
    }

    private static function convertToWords($number)
    {
        if ($number == 0) {
            return '';
        }

        if ($number < 20) {
            return self::$ones[$number];
        }

        if ($number < 100) {
            $tensDigit = (int) ($number / 10);
            $onesDigit = $number % 10;
            return self::$tens[$tensDigit] . ($onesDigit > 0 ? ' ' . self::$ones[$onesDigit] : '');
        }

        if ($number < 1000) {
            $hundreds = (int) ($number / 100);
            $remainder = $number % 100;
            return self::$ones[$hundreds] . ' Hundred' . ($remainder > 0 ? ' ' . self::convertToWords($remainder) : '');
        }

        // Handle thousands (1,000 to 99,999)
        if ($number < 100000) {
            $thousands = (int) ($number / 1000);
            $remainder = $number % 1000;
            $result = '';
            if ($thousands > 0) {
                $result = self::convertToWords($thousands) . ' Thousand';
            }
            if ($remainder > 0) {
                $result .= ($result ? ' ' : '') . self::convertToWords($remainder);
            }
            return $result;
        }

        // Handle lakhs (100,000 to 99,99,999)
        if ($number < 10000000) {
            $lakhs = (int) ($number / 100000);
            $remainder = $number % 100000;
            $result = '';
            if ($lakhs > 0) {
                $result = self::convertToWords($lakhs) . ' Lakh';
            }
            if ($remainder > 0) {
                $result .= ($result ? ' ' : '') . self::convertToWords($remainder);
            }
            return $result;
        }

        // Handle crores (1,00,00,000 and above)
        $crores = (int) ($number / 10000000);
        $remainder = $number % 10000000;
        $result = '';
        if ($crores > 0) {
            $result = self::convertToWords($crores) . ' Crore';
        }
        if ($remainder > 0) {
            $result .= ($result ? ' ' : '') . self::convertToWords($remainder);
        }
        return $result;
    }
}
