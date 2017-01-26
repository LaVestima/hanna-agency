<?php

namespace AppBundle\Controller;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Customers;

class DefaultController extends Controller {
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/invoices", name="invoices")
     */
    public function invoicesAction() { // To be deleted later
        //get data from database

		//testing playground
		$countries = $this->getDoctrine()
			->getRepository('AppBundle:Countries')
			->findAll();
		$cities = $this->getDoctrine()
			->getRepository('AppBundle:Cities')
			->findAll();
		$serializer = SerializerBuilder::create()->build();
		$jsonCountries = $serializer->serialize($countries, 'json');
		$jsonCities = $serializer->serialize($cities, 'json');

//		return new Response($jsonContent);
		return $this->render('default/invoices.html.twig', array(
			'jsonCountries' => $jsonCountries,
			'jsonCities' => $jsonCities,
			'cities' => $cities,
		));
    }

	/**
	 * @Route("/product_list", name="product_list")
	 */
	public function productListAction() {
		$products = $this->getDoctrine()->getRepository('AppBundle:Products')
			->findAll();

		return $this->render('default/product_list.html.twig', array(
			'products' => $products,
		));
	}

	/**
	 * @Route("/product/{pathSlug}", name="product", defaults={"pathSlug": 0})
	 */
	public function productAction($pathSlug) {
		$product = $this->getDoctrine()->getRepository('AppBundle:Products')
			->findBy(array(
				'pathSlug' => $pathSlug
			));

		return $this->render('default/product.html.twig', array(
			'products' => $product,
		));
	}
}
