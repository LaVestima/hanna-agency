<?php

namespace LaVestima\HannaAgency\CustomerBundle\Controller\Helper;

use RandomLib\Factory;

class CustomerCrudHelper {
    public static function generateIdentificationNumber() {
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();

        $identificationNumber = $generator->generateString(10, '0123456789');

        return $identificationNumber;
    }
}