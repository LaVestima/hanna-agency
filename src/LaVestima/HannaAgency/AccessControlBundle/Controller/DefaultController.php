<?php

namespace LaVestima\HannaAgency\AccessControlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AccessControlBundle:Default:index.html.twig');
    }
}
