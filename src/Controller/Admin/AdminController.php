<?php

namespace App\Controller\Admin;

use App\Controller\Infrastructure\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends BaseController
{
    /**
     * @Route("/admin")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function index()
    {
        return $this->redirectToRoute('admin_panel_index');
    }
}
