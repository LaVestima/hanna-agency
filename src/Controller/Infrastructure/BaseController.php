<?php

namespace App\Controller\Infrastructure;

use App\Controller\Infrastructure\Action\ActionControllerTrait;
use App\Controller\Infrastructure\Action\ListActionControllerTrait;
use App\Controller\Infrastructure\Action\NewActionControllerTrait;
use App\Repository\StoreRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BaseController extends AbstractController
{
    use ActionControllerTrait;
    use ListActionControllerTrait;
    use NewActionControllerTrait;

    /**
     * @var Request
     */
    protected $request;
    protected $isAsync;
    protected $session;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    private $storeRepository;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @required
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    /**
     * @required
     * @param StoreRepository $storeRepository
     */
    public function setStoreRepository(StoreRepository $storeRepository): void
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * @required
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @required
     * @param PaginatorInterface $paginator
     */
    public function setPaginator(PaginatorInterface $paginator): void
    {
        $this->paginator = $paginator;
    }


    public function render(string $view, array $parameters = array(), Response $response = null): Response
    {
        $cart = $this->session->get('cart') ?? [];
        $cartTotal = 0;

        foreach ($cart as $item) {
            $cartTotal += $item['quantity'];
        }

        $parameters['cartTotal'] = $cartTotal;

        return parent::render($view, $parameters, $response);
    }

    /**
     * @return bool
     */
    public function isEnvDev(): bool
    {
        return $this->getParameter('kernel.environment') === 'dev';
    }

    /**
     * Check if user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->authorizationChecker
            ->isGranted('ROLE_ADMIN');
    }

    /**
     * @return bool
     */
    protected function isStore(): bool
    {
        return $this->getStore() !== null;
    }

    /**
     * @return mixed
     */
    protected function getStore()
    {
        return $this->getUser()->getStores()[0];
    }

    /**
     * Deny access when request is not asynchronous.
     *
     * @throws AccessDeniedHttpException
     */
    protected function denyNonXhrs(): void
    {
        if (!$this->request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }
    }
}
