<?php

namespace App\Helper;

class CrudHelper {
    public static function generatePathSlug(string $entityName) {
        $pathSlug = str_replace(' ', '-', $entityName);
        $pathSlug = strtolower($pathSlug);

        return $pathSlug;
    }
}
