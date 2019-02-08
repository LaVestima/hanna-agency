<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('url_decode', [$this, 'urlDecode'])
        ];
    }

    public function urlDecode($url)
    {
        return urldecode($url);
    }
}