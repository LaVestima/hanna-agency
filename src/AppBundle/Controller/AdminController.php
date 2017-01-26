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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Query\QueryBuilder;
use RandomLib\Factory;
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
		$customer = $this->getDoctrine()
			->getRepository('AppBundle:Customers')
			->findOneBy(array('pathSlug' => $pathSlug));

		if (!$customer) {
			$this->addFlash('notice', 'Wrong customer!');
			$this->addFlash('noticeType', 'negative');

			return $this->redirectToRoute('admin_customer_list');
		}
		else {
			$invoices = $this->getDoctrine()
				->getRepository('AppBundle:Invoices')
				->createQueryBuilder('i')
				->where('i.idCustomers = :idCustomers')
				->setParameter('idCustomers', $customer->getId())
				->orderBy('i.dateIssued', 'DESC')
				->getQuery()
				->getResult();
		}

		return $this->render('admin/customer.html.twig', array(
			'customer' => $customer,
			'invoices' => $invoices,
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

			if ($customer->getIdCities()->getIdCountries() != $customer->getIdCountries()) {
				$this->addFlash('notice', 'City does not match with the country!');
				$this->addFlash('noticeType', 'negative');
				return $this->redirectToRoute('admin_add_customer');
			}

			$factory = new Factory();
			$generator = $factory->getMediumStrengthGenerator();

			do {
				$randomIdentificationNumber = $generator->generateString(10, '0123456789');
				$customerCheck = $this->getDoctrine()
					->getRepository('AppBundle:Customers')
					->findBy(array('identificationNumber' => $randomIdentificationNumber));
			} while ($customerCheck);

			$customer->setIdentificationNumber($randomIdentificationNumber);

			do {
				$randomPathSlug = $generator->generateString(50, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
				$customerCheck = $this->getDoctrine()
					->getRepository('AppBundle:Customers')
					->findBy(array('pathSlug' => $randomPathSlug));
			} while ($customerCheck);

			$customer->setPathSlug($randomPathSlug);

			$em = $this->getDoctrine()->getManager();
			$em->persist($customer);
			$em->flush();
			
			$this->addFlash('notice', 'User added successfully!');
			$this->addFlash('noticeType', 'positive');

			// just for test: Send an email
			$message = \Swift_Message::newInstance()
				->setSubject('Customer added!')
				->setFrom('lavestima@lavestima.com')
				->setTo('lavestima@gmail.com')
				->setBody("New customer has just been added." . "\n"
					. $customer->getFirstName() . ' ' . $customer->getLastName() . "\n"
					. $customer->getStreet() . "\n"
					. $customer->getPostalCode() . ' ' . $customer->getIdCities()->getName() . "\n"
					. $customer->getIdCountries()->getName()
				);
			$this->get('mailer')->send($message);
			// end of test

			return $this->redirectToRoute('admin_customer_list');
		}

		return $this->render('admin/add_customer.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/edit_customer/{pathSlug}", name="admin_edit_customer", defaults={"pathSlug": 0})
	 */
	public function editCustomerAction(Request $request, $pathSlug) {
		$em = $this->getDoctrine()->getManager();
		$customer = $em->getRepository('AppBundle:Customers')
			->findOneBy(array('pathSlug' => $pathSlug));

		if (!$customer) {
			$this->addFlash('notice', 'No customer found!');
			$this->addFlash('noticeType', 'negative');
			return $this->redirectToRoute('admin_customer_list');
		}

		$form = $this->createForm(CustomersType::class, $customer);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$customer = $form->getData();
//			$messageSubject = "";

			if ($form->get('delete')->isClicked()) {
				$em->remove($customer);
//				$messageSubject = "Customer deleted!";
				$this->addFlash('notice', 'User deleted successfully!');
			}
			else if ($form->get('add')->isClicked()) {
//				$messageSubject = "Customer edited!";
				$this->addFlash('notice', 'User edited successfully!');
			}
			
			$em->flush();

			$this->addFlash('noticeType', 'positive');

//			$message = \Swift_Message::newInstance()
//				->setSubject($messageSubject)
//				->setFrom('lavestima@lavestima.com')
//				->setTo('lavestima@gmail.com')
//				->setBody("New customer has just been added." . "\n"
//					. $customer->getFirstName() . ' ' . $customer->getLastName() . "\n"
//					. $customer->getStreet() . "\n"
//					. $customer->getPostalCode() . ' ' . $customer->getIdCities()->getName() . "\n"
//					. $customer->getIdCountries()->getName()
//				);
//			$this->get('mailer')->send($message);

			return $this->redirectToRoute('admin_customer_list');
		}

		return $this->render('admin/edit_customer.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
	 * @Route("/country_city_list", name="admin_country_city_list")
	 */
	public function CountryCityListAction() {
		$countries = $this->getDoctrine()->getRepository('AppBundle:Countries')
			->findAll();

		$cities = $this->getDoctrine()->getRepository('AppBundle:Cities')
			->findAll();

		return $this->render('admin/country_city_list.html.twig', array(
			'countries' => $countries,
			'cities' => $cities,
		));
	}
}