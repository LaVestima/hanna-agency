<?php

namespace App\Enum;

use ReflectionClass;

class Genders
{
    public const OTHER = 'gender.other';
    public const FEMALE = 'gender.female';
    public const MALE = 'gender.male';

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
