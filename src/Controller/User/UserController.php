<?php

namespace App\Controller\User;

use App\Controller\Infrastructure\BaseController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
//        CustomerCrudControllerInterface $customerCrudController
    ) {
        $this->userRepository = $userRepository;
//        $this->customerCrudController = $customerCrudController;
    }

    /**
     * @Route("/user/list", name="user_list")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * {@inheritdoc}
     * @throws \Exception
     */
    public function listAction(Request $request)
    {
//        $this->setQuery($this->userRepository->setAlias('u')
//            ->readAllEntities()
//            ->onlyUndeleted()
////            ->readAllUndeletedEntities()
//            ->join('idRoles', 'r')
//            ->orderBy('login', 'ASC')
//            ->getQuery());
        $this->setView('User/list.html.twig');
//        $this->setActionBar([
//            [
//                'label' => 'New User',
//                'path' => 'user_new',
//            ],
//            [
//                'label' => 'Deleted Users',
//                'path' => 'user_deleted_list',
//            ],
//            [
//                'label' => 'Login History',
//                'path' => 'access_control_login_attempt_list',
//            ]
//        ]);

        return parent::baseList($request);
    }
}