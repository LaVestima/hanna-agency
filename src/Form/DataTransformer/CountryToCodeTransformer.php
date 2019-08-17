<?php

namespace App\Form\DataTransformer;

use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CountryToCodeTransformer implements DataTransformerInterface
{
    private $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function reverseTransform($code)
    {
        if (!$code) { return; }

        $country = $this->countryRepository
            ->findOneBy([
                'code' => $code
            ]);

        if (!$country) {
            throw new TransformationFailedException();
        }

        return $country;
    }

    public function transform($country)
    {
        if (!$country || !$country instanceof Country) {
            return '';
        }

        return $country->getCode();
    }
}
