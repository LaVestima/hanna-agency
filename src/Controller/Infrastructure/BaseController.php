<?php

namespace App\Controller\Infrastructure;

use App\Controller\Infrastructure\Action\ActionControllerTrait;
use App\Controller\Infrastructure\Action\ListActionControllerTrait;
use App\Controller\Infrastructure\Action\NewActionControllerTrait;
use App\Controller\Infrastructure\Action\ShowActionControllerTrait;
use App\Repository\ProducerRepository;
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
    use ShowActionControllerTrait;
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

    private $producerRepository;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @required
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @required
     * @param ProducerRepository $producerRepository
     */
    public function setProducerRepository(ProducerRepository $producerRepository)
    {
        $this->producerRepository = $producerRepository;
    }

    /**
     * @required
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @required
     * @param PaginatorInterface $paginator
     */
    public function setPaginator(PaginatorInterface $paginator)
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

        $parameters['actionBar'] = $this->actionBar;
        $parameters['cartTotal'] = $cartTotal;

        return parent::render($view, $parameters, $response);
    }

    /**
     * @return bool
     */
    public function isEnvDev()
    {
        return $this->getParameter('kernel.environment') === 'dev';
    }

    /**
     * Check if user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->authorizationChecker
            ->isGranted('ROLE_ADMIN');
    }

    /**
     * @return bool
     */
    protected function isProducer()
    {
        return $this->getProducer() !== null;
    }

    /**
     * @return mixed
     */
    protected function getProducer()
    {
        return $this->producerRepository
            ->readOneEntityBy([
                'user' => $this->getUser()
            ])->getResult();
    }

    /**
     * Deny access when request is not asynchronous.
     *
     * @throws AccessDeniedHttpException
     */
    protected function denyNonXhrs()
    {
        if (!$this->request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }
    }
}
