<?php

namespace App\Helper;

class RandomHelper
{
    public static function generateString(int $length = 8, string $characters = 'NLU')
    {
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomRange = [];

            if (strpos($characters, 'N') !== false) {
                $randomRange = array_merge($randomRange, range(48, 57));
            }

            if (strpos($characters, 'L') !== false) {
                $randomRange = array_merge($randomRange, range(97, 122));
            }

            if (strpos($characters, 'U') !== false) {
                $randomRange = array_merge($randomRange, range(65, 90));
            }

            $randomString .= chr($randomRange[array_rand($randomRange)]);
        }

        return $randomString;
    }
}
