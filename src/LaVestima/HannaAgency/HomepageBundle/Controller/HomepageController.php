<?php
/**
 * Created by PhpStorm.
 * User: lavestima
 * Date: 18.03.17
 * Time: 20:53
 */

namespace LaVestima\HannaAgency\HomepageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller {
	public function indexAction() {
		return $this->render('HomepageBundle:Homepage:index.html.twig');
	}
}