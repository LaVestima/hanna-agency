<?php

namespace LaVestima\HannaAgency\ProducerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProducerBundle:Default:index.html.twig');
    }
}
