<?php

namespace App\Enum;

use ReflectionClass;

class ParameterType
{
    public const TEXTUAL = 1;
    public const CATEGORICAL_NOMINAL = 2;
    public const CATEGORICAL_ORDINAL = 3;
    public const NUMERICAL_DISCRETE = 4;
    public const NUMERICAL_CONTINUOUS = 5;
    public const OTHER = 99;

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
