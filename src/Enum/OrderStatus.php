<?php

namespace App\Enum;

use ReflectionClass;

class OrderStatus
{
    public const PLACED = 'order_status.placed';
    public const PAID = 'order_status.paid';
    public const SHIPPED = 'order_status.shipped';
    public const COMPLETED = 'order_status.completed';
    public const CANCELLED = 'order_status.cancelled';

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
