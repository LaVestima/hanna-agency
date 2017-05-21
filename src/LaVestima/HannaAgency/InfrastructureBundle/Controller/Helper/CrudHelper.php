<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller\Helper;

use RandomLib\Factory;

class CrudHelper {
	public static function generatePathSlug() {
		$factory = new Factory();
		$generator = $factory->getMediumStrengthGenerator();
		
		$pathSlug = $generator->generateString(50, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
		
		return $pathSlug;
	}
}