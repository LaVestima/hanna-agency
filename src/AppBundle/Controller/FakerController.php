<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 16.01.17
 * Time: 20:38
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Countries;
use AppBundle\Entity\Customers;
use Faker\Factory;
use Faker\Provider\nl_NL\Company;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FakerController extends Controller{
	/**
	 * @Route("/customer", name="faker_customer")
	 */
	public function fakerCustomerAction() {
		$faker = Factory::create();
		$customers = [];
		$counter = 1;

		for ($i = 0; $i < $counter; $i++) {
			$tmpCustomer = new Customers();

			$em= $this->getDoctrine()->getManager();

			$customers[] = array(
				"identificationNumber" => $faker->unique()->numerify('##########'),
				"firstName" => $faker->firstName,
				"lastName" => $faker->lastName,
				"gender" => $faker->randomElement(array('M', 'F', 'O')),
				"company" => $faker->optional()->company,
				"vat" => Company::vat(),
				"country" => $faker->numberBetween(1, 10),
				"city" => $faker->numberBetween(1, 15),
				"address" => $faker->unique()->streetAddress,
				"postal" => $faker->numerify('##-###'),
				"email" => $faker->email,
				"phone" => $faker->phoneNumber,
				"currency" => $faker->numberBetween(1, 8),
				"pathSlug" => $faker->regexify('([A-Za-z0-9]{49})\w'),
			);
//			echo $customers[$i]["gender"];

			$tmpCustomer->setIdentificationNumber($customers[$i]["identificationNumber"]);
			$tmpCustomer->setFirstName($customers[$i]["firstName"]);
			$tmpCustomer->setLastName($customers[$i]["lastName"]);
			$tmpCustomer->setGender($customers[$i]["gender"]);
			$tmpCustomer->setCompanyName($customers[$i]["company"]);
			$tmpCustomer->setVat($customers[$i]["vat"]);
			$tmpCustomer->setStreet($customers[$i]["address"]);
			$tmpCustomer->setPostalCode($customers[$i]["postal"]);
			$tmpCustomer->setEmail($customers[$i]["email"]);
			$tmpCustomer->setPhone($customers[$i]["phone"]);
			$tmpCustomer->setPathSlug($customers[$i]["pathSlug"]);
			$country = $this->getDoctrine()->getRepository('AppBundle:Countries')
				->find($customers[$i]["country"]);
			$tmpCustomer->setIdCountries($country);
			do {
				$customers[$i]["city"] = $faker->numberBetween(1, 15);
				$city = $this->getDoctrine()->getRepository('AppBundle:Cities')
					->find($customers[$i]["city"]);
				$tmpCustomer->setIdCities($city);
			} while ($city->getIdCountries()->getId() != $country->getId());
			$currency = $this->getDoctrine()->getRepository('AppBundle:Currencies')
				->find($customers[$i]["currency"]);
			$tmpCustomer->setIdCurrencies($currency);

			$em->persist($tmpCustomer);
			$em->flush();
		}
		
		return $this->render('faker/customer.html.twig', array(
			'customers' => $customers,
		));
	}
}