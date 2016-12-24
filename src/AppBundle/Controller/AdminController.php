<?php
/**
 * Created by PhpStorm.
 * User: Szymon
 * Date: 01-12-2016
 * Time: 20:22
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Customers;
use AppBundle\Form\CustomersType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends Controller {
    /**
     * @Route("/panel", name="admin_panel")
     */
    public function panelAction() {
        return $this->render('admin/panel.html.twig');
    }

	/**
	 * @Route("/customer_list", name="admin_customer_list")
	 */
	public function customerListAction() {
		$customers = $this->getDoctrine()
			->getRepository('AppBundle:Customers')
			->findAll();

		return $this->render('admin/customer_list.html.twig', array(
			'customers' => $customers
		));
	}

	/**
	 * @Route("/customer/{pathSlug}", name="admin_customer", defaults={"pathSlug": 0})
	 */
	public function customerAction($pathSlug) {
//		$customer = new Customers();

		$customer = $this->getDoctrine()
			->getRepository('AppBundle:Customers')
			->findBy(array('pathSlug' => $pathSlug));

		if (!$customer) {
//			$this->addFlash(
//                'productListError',
//                'Wrong Product Chosen!'
//            );
		}

		return $this->render('admin/customer.html.twig', array(
			'customers' => $customer
		));
	}

	/**
	 * @Route("/add_customer", name="admin_add_customer")
	 */
	public function addCustomerAction(Request $request) {
		$customer = new Customers();

		$form = $this->createForm(CustomersType::class, $customer);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$customer = $form->getData();

			$randomIdentificationNumber = rand(1000000000, 9999999999);// TODO: check uniqueness
			$customer->setIdentificationNumber((string)$randomIdentificationNumber);

			do {
				$randomPathSlug = bin2hex(random_bytes(25));
				$customerCheck = $this->getDoctrine()
					->getRepository('AppBundle:Customers')
					->findBy(array('pathSlug' => $randomPathSlug));
			} while ($customerCheck);

			$customer->setPathSlug($randomPathSlug);

			$customer->setId(5);

			$em = $this->getDoctrine()->getManager();
			$em->persist($customer);
			$em->flush();

			return $this->render('admin/add_customer.html.twig', array(
//				'form' => $form->createView(),
				'message' => 'User added!',
			));
		}

		return $this->render('admin/add_customer.html.twig', array(
			'form' => $form->createView(),
		));
	}
}