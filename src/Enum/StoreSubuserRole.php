<?php

namespace App\Enum;

use ReflectionClass;

class StoreSubuserRole
{
    public const ROLE_VIEW_DASHBOARD = 'ROLE_VIEW_DASHBOARD';

    public const ROLE_READ_MESSAGES = 'ROLE_READ_MESSAGES';
    public const ROLE_WRITE_MESSAGES = 'ROLE_WRITE_MESSAGES';

    public const ROLE_ADD_PRODUCTS = 'ROLE_ADD_PRODUCTS';
    public const ROLE_EDIT_PRODUCTS = 'ROLE_EDIT_PRODUCTS';
    public const ROLE_DELETE_PRODUCTS = 'ROLE_DELETE_PRODUCTS';

    public const ROLE_ORDER_VIEW = 'ROLE_ORDER_VIEW';

    public const ROLE_VIEW_STATISTICS = 'ROLE_VIEW_STATISTICS';

    public const ROLE_STORE_ADMIN = 'ROLE_STORE_ADMIN';

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
