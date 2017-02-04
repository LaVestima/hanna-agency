<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 26.01.17
 * Time: 00:09
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Orders;
use AppBundle\Entity\OrdersProducts;
use AppBundle\Entity\Products;
use AppBundle\Form\OrdersProductsType;
use AppBundle\Form\ProductsType;
use RandomLib\Factory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {
	/**
	 * @Route("/invoice_list", name="user_invoice_list")
	 */
	public function invoiceListAction() {
		$invoices = $this->getDoctrine()->getRepository('AppBundle:Invoices')
			->findAll();

		return $this->render('user/invoice_list.html.twig', array(
			'invoices' => $invoices
		));
	}

	/**
	 * @Route("/invoice/{id}", name="user_invoice")
	 */
	public function InvoiceAction($id) { // TODO: change id to path_slug
		$em = $this->getDoctrine()->getManager();
		$qb = $em->createQueryBuilder();
		$qb->select('i.name AS iName' ,
			'i.dateIssued',
			'ip.quantity',
			'ip.discount',
			'ip.priceFinal',
			'p.name AS pName'
		)
			->from('AppBundle:Invoices', 'i')
			->join('AppBundle:InvoicesProducts', 'ip', 'WITH', 'ip.idInvoices = i.id')
			->join('AppBundle:Products', 'p', 'WITH', 'p.id=ip.idProducts')
			->where('i.id = :id')
			->orderBy('i.dateIssued', 'DESC')
			->setParameter('id', $id)
		;
		$query = $qb->getQuery();

		$invoice = $query->getResult();

		if (!$invoice) {
			$this->addFlash('notice', 'No invoice found!');
			$this->addFlash('noticeType', 'negative');
			return $this->redirectToRoute('user_invoice_list');
		}

		return $this->render('user/invoice.html.twig', array(
			'invoice' => $invoice,
		));
	}

	/**
	 * @Route("/order_list", name="user_order_list")
	 */
	public function orderListAction() {
		$orders = $this->getDoctrine()->getRepository('AppBundle:Orders')
			->findAll();

		return $this->render('user/order_list.html.twig', array(
			'orders' => $orders
		));
	}

	/**
	 * @Route("/order/{pathSlug}", name="user_order")
	 */
	public function orderAction() { // TODO: change id to path_slug

	}

	/**
	 * @Route("/add_order", name="user_add_order")
	 */
	public function addOrderAction(Request $request) {
		$ordersProduct = new OrdersProducts();

		$doctrine = $this->getDoctrine();

		$products = $doctrine->getRepository('AppBundle:Products')
			->findAll(); // TODO: change to ProductsSizes

		foreach ($products as $key => $product) {
			$ordersProduct->getIdProducts()->add($product);
		}

		$form = $this->createForm(OrdersProductsType::class, $ordersProduct);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// if no products selected, error

			$order = new Orders();
			$user = $this->getUser();
			$order->setDatePlaced(new \DateTime("now"));
			$order->setIdUsers($user);

			$factory = new Factory();
			$generator = $factory->getMediumStrengthGenerator();

			do {
				$randomPathSlug = $generator->generateString(50, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
				$orderCheck = $this->getDoctrine()
					->getRepository('AppBundle:Orders')
					->findBy(array('pathSlug' => $randomPathSlug));
			} while ($orderCheck);
			$order->setPathSlug($randomPathSlug);

			if (!$user->getIsAdmin()) {
				$customer = $this->getDoctrine()->getRepository('AppBundle:Customers')
					->findOneBy(array('idUsers' => $user));
				$order->setIdCustomers($customer);
			}
			elseif ($user->getIsAdmin()) {

			}

			$em = $this->getDoctrine()->getManager();
			$em->persist($order);

			$products = $form->getData();

			foreach ($products->getIdProducts() as $key => $idProduct) {
				if ($form['idProducts'][$key]['isSelected']->getData()) {
//					echo '<br>'.'<br>'.$form['idProducts'][$key]['quantity']->getData();

					$ordersProduct = new OrdersProducts();
					$ordersProduct->setIdOrders($order);
					$ordersProduct->setIdProducts($idProduct);
					$ordersProduct->setPriceFinal(0); // TODO: change
					$ordersProduct->setDiscount(0); // TODO: change
					$ordersProduct->setQuantity($form['idProducts'][$key]['quantity']->getData());

					$em->persist($ordersProduct);
				}
			}
			$em->flush();
		}

		return $this->render('user/add_order.html.twig', array(
			'form' => $form->createView(),
		));
	}
}