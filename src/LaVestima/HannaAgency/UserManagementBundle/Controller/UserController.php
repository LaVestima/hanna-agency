<?php

namespace LaVestima\HannaAgency\UserManagementBundle\Controller;

use LaVestima\HannaAgency\CustomerBundle\Controller\Crud\CustomerCrudControllerInterface;
use LaVestima\HannaAgency\InfrastructureBundle\Controller\BaseController;
use LaVestima\HannaAgency\UserManagementBundle\Controller\Crud\UserCrudControllerInterface;
use LaVestima\HannaAgency\UserManagementBundle\Entity\Users;
use LaVestima\HannaAgency\UserManagementBundle\Form\UserType;
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
                'icon' => 'fa-plus'
            ],
            [
                'label' => 'Deleted Users',
                'path' => 'user_deleted_list',
                'icon' => 'fa-close'
            ],
            [
                'label' => 'Login History',
                'path' => 'access_control_login_attempt_list',
                'icon' => 'fa-history'
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
        $this->setView('@UserManagement/User/deletedList.html.twig');
        $this->setActionBar([
            [
                'label' => 'User List',
                'path' => 'user_list',
                'icon' => 'fa-chevron-left'
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

    /**
     * User New Action.
     *
     * @param Request $request
     *
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
	public function newAction(Request $request)
    {
        $user = new Users();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPasswordHash('');

            // TODO: send the email with password reset

            $this->get('user_crud_controller')
                ->createEntity($user);

            $this->addFlash('success', 'User Created!');

            return $this->redirectToRoute('user_list');
        }

        $this->setView('@UserManagement/User/new.html.twig');
        $this->setForm($form);
        $this->setActionBar([
            [
                'label' => 'List',
                'path' => 'user_list',
                'icon' => 'fa-chevron-left'
            ]
        ]);

        return $this->baseNewAction();
    }
}
