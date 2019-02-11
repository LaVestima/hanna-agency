<?php

namespace App\Controller\Infrastructure;

use App\Controller\Infrastructure\Action\ActionControllerTrait;
use App\Controller\Infrastructure\Action\ListActionControllerTrait;
use App\Controller\Infrastructure\Action\NewActionControllerTrait;
use App\Controller\Infrastructure\Action\ShowActionControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    use ActionControllerTrait;
    use ListActionControllerTrait;
    use ShowActionControllerTrait;
    use NewActionControllerTrait;

    protected $isAsync;

    /**
     * @return bool
     */
    public function isDevEnvironment()
    {
        if ($this->get('kernel')->getEnvironment() === 'dev') {
            return true;
        }

        return false;
    }

    /**
     * Check if user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->get('security.authorization_checker')
            ->isGranted('ROLE_ADMIN');
    }
}
