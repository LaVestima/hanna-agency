<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserManagementBundle:Default:index.html.twig');
    }
}
