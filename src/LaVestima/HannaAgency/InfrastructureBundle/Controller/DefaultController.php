<?php

namespace LaVestima\HannaAgency\InfrastructureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('InfrastructureBundle:Default:index.html.twig');
    }
}
