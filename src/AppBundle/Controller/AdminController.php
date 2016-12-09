<?php
/**
 * Created by PhpStorm.
 * User: Szymon
 * Date: 01-12-2016
 * Time: 20:22
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends Controller {
    /**
     * @Route("/", name="admin_index")
     */
    public function adminAction() {
        return new Response('<html><body>Admin page!</body></html>');
    }
}