<?php

namespace App\Helper;

class CrudHelper {
    public static function generatePathSlug(string $entityName) {
        $pathSlug = $entityName;
        $pathSlug = str_replace(' ', '-', $pathSlug);
        $pathSlug = filter_var($pathSlug, FILTER_SANITIZE_URL);

        $pathSlug = strtolower($pathSlug);

        return $pathSlug;
    }
}
