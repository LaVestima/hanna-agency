<?php

namespace LaVestima\HannaAgency\MoneyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MoneyBundle:Default:index.html.twig');
    }
}
