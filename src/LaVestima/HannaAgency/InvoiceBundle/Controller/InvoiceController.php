<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 12.03.17
 * Time: 14:34
 */

namespace LaVestima\HannaAgency\InvoiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InvoiceController extends Controller {
	public function listAction() {
		return $this->render('@Invoice/Invoice/list.html.twig');
	}
}