<?php

namespace App\EventSubscriber;

use App\Entity\PageVisit;
use App\Repository\PageVisitRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class PageVisitSubscriber implements EventSubscriberInterface
{
    private $pageVisitRepository;
    private $security;

    public function __construct(
        PageVisitRepository $pageVisitRepository,
        Security $security
    ) {
        $this->pageVisitRepository = $pageVisitRepository;
        $this->security = $security;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
//        var_dump($request);die;
//        var_dump(json_encode($request->query->all()));die;

        if ($request->attributes->get('_route')) {
            $pageVisit = new PageVisit();
            $pageVisit->setUser($this->security->getUser());
            $pageVisit->setRoute($request->attributes->get('_route'));
            $pageVisit->setRouteParams(json_encode($request->attributes->get('_route_params')));
            $pageVisit->setQueryParams(json_encode($request->query->all()));

            $pageVisit->setClientIp($request->getClientIp());

            $this->pageVisitRepository->createEntity($pageVisit);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
