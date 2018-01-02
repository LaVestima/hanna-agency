<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller;

use LaVestima\HannaAgency\CustomerBundle\Controller\Crud\CustomerCrudControllerInterface;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    private $userCrudController;
    private $customerCrudController;

    /**
     * UserController constructor.
     *
     * @param UserCrudControllerInterface $userCrudController
     * @param CustomerCrudControllerInterface $customerCrudController
     */
    public function __construct(
        UserCrudControllerInterface $userCrudController,
        CustomerCrudControllerInterface $customerCrudController
    ) {
        $this->userCrudController = $userCrudController;
        $this->customerCrudController = $customerCrudController;
    }

    /**
     * User List Action.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $this->setView('@UserManagement/User/list.html.twig');
        $this->setActionBar([
            [
                'label' => 'New User',
                'path' => 'user_new',
            ],
            [
                'label' => 'Deleted Users',
                'path' => 'user_deleted_list',
            ],
            [
                'label' => 'Login History',
                'path' => 'access_control_login_attempt_list',
            ]
        ]);

        return parent::baseListAction($request);
	}

    /**
     * User Deleted List Action.
     *
     * @param Request $request
     *
     * @return mixed
     */
	public function deletedListAction(Request $request)
    {
        $this->setView('@UserManagement/User/list.html.twig');
        $this->setActionBar([
            [
                'label' => '< User List',
                'path' => 'user_list'
            ]
        ]);

        return parent::baseListAction($request);
    }

    /**
     * User Show Action.
     *
     * @param $pathSlug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function showAction(string $pathSlug)
    {
		$user = $this->userCrudController
			->readOneEntityBy(['pathSlug' => $pathSlug])
            ->getResult();

        $customer = null;

		if ($user->getIdRoles()->getCode() === 'ROLE_CUSTOMER') {
			$customer = $this->get('customer_crud_controller')
				->readOneEntityBy(['idUsers' => $user])
                ->getResult();
		}

		$userSettings = $this->get('user_setting_crud_controller')
            ->readOneEntityBy(['idUsers' => $user])
            ->getResult();
		
		return $this->render('@UserManagement/User/show.html.twig', [
			'user' => $user,
			'customer' => $customer,
            'userSettings' => $userSettings,
		]);
	}

	public function newAction(Request $request)
    {
        return $this->render('@UserManagement/User/new.html.twig', [

        ]);
    }
}
