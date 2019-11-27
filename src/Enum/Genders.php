<?php

namespace App\Enum;

use ReflectionClass;

class Genders
{
    public const OTHER = 1;
    public const FEMALE = 2;
    public const MALE = 3;

    public static function getConstants()
    {
        try {
            $reflector = new ReflectionClass(self::class);
        } catch (\ReflectionException $e) {
            return [];
        }

        return $reflector->getConstants();
    }
}
