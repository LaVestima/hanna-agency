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
//			$this->addFlash(
//                'productListError',
//                'Wrong Product Chosen!'
//            );
		}
		else {
//			$invoices = $this->getDoctrine()
//				->getRepository('AppBundle:Invoices')
//				->findBy(array(
//					'idCustomers' => $customer->getId(),
//				));
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
			
			$this->addFlash(
				'notice', 'User added successfully!'
			);
			$this->addFlash(
				'noticeType', 'positive'
			);

			// just for test: Send an email
			$message = \Swift_Message::newInstance()
				->setSubject('Test for adding a customer!')
				->setFrom('lavestima@lavestima.com')
				->setTo('lavestima@gmail.com')
				->setBody('This is the body of the message, there is nothing interesting here.');
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
			$this->addFlash(
				'notice', 'No customer found!'
			);
			$this->addFlash(
				'noticeType', 'negative'
			);
			 return $this->redirectToRoute('admin_customer_list');
		}

		$form = $this->createForm(CustomersType::class, $customer);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$customer = $form->getData();

			if ($form->get('delete')->isClicked()) {
				$em->remove($customer);

				$this->addFlash(
					'notice', 'User deleted successfully!'
				);
			}
			else if ($form->get('add')->isClicked()) {
				$this->addFlash(
					'notice', 'User edited successfully!'
				);
			}
			
			$em->flush();

			$this->addFlash(
				'noticeType', 'positive'
			);

			return $this->redirectToRoute('admin_customer_list');
		}

		return $this->render('admin/edit_customer.html.twig', array(
			'form' => $form->createView(),
		));
	}
}