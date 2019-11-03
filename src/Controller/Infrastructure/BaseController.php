<?php

namespace App\Controller\Infrastructure;

use App\Controller\Infrastructure\Action\ActionControllerTrait;
use App\Controller\Infrastructure\Action\ListActionControllerTrait;
use App\Controller\Infrastructure\Action\NewActionControllerTrait;
use App\Entity\Cart;
use App\Repository\StoreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BaseController extends AbstractController
{
    use ActionControllerTrait;
    use ListActionControllerTrait;
    use NewActionControllerTrait;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

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
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @required
     * @param RequestStack $requestStack
     */
    public function setRequest(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @required
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
        $parameters['cartTotal'] = $this->entityManager->getRepository(Cart::class)
            ->getTotalSessionQuantity($this->request->getSession()->getId()) ?? 0;

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
        return $this->getUser() ? $this->getUser()->getStores()[0] : null;
    }

    /**
     * Deny access when request is not asynchronous.
     *
     * @throws AccessDeniedHttpException
     */
    protected function denyNonXhrs(Request $request): void
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }
    }
}
