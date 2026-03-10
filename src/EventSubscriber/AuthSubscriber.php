<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class AuthSubscriber implements EventSubscriberInterface
{
    private const PUBLIC_ROUTES = ['login', 'auth_verify'];

    public function __construct(private RouterInterface $router) {}

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['onKernelRequest', 10]];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) return;

        $request = $event->getRequest();
        $route   = $request->attributes->get('_route');

        if (in_array($route, self::PUBLIC_ROUTES, true)) return;

        $session = $request->getSession();

        if (!$session->get('user_uid')) {
            $event->setResponse(
                new RedirectResponse($this->router->generate('login'))
            );
        }
    }
}
