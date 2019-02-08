<?php

namespace App\Controller\Homepage;

use App\Controller\Infrastructure\BaseController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends BaseController
{
    /**
     * @Route("/", name="homepage_homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepage()
    {
        return $this->render('Homepage/homepage.html.twig');
    }

    /**
     * @Route("/contact", name="homepage_contact")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact()
    {
        return $this->render('Homepage/contact.html.twig');
    }
}