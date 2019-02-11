<?php

namespace App\Controller\User\Async;


use App\Controller\Infrastructure\Async\BaseAsyncController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserAsyncController extends BaseAsyncController
{
    private $userRepository;

    /**
     * UserAsyncController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/user/Async/list", name="user_async_list", options={"expose"=true})
     *
     * @return mixed|void
     */
    public function list(Request $request)
    {
        return parent::list($request);
    }

    /**
     * User Async Generic List Action.
     *
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function genericList(Request $request)
    {
        $filters = $request->get('filters');

        $this->userRepository
            ->setAlias('u');

        if (empty($filters)) {
            $this->userRepository
                ->readAllEntities();
        } else {
            $this->userRepository
                ->readEntitiesBy(
                    $this->convertFiltersToCrudCondition($filters)
                );
        }

        if ($this->isListDeleted) {
            $this->userRepository
                ->onlyDeleted();
        } else {
            $this->userRepository
                ->onlyUndeleted();
        }

        $this->setQuery($this->userRepository
            ->join('idRoles', 'r')
            ->orderBy('login', 'ASC')
            ->getQuery()
        );
        $this->setView('User/Async/list.html.twig');

        return parent::baseList($request);
    }
}