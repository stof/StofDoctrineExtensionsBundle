<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Gedmo\IpTraceable\IpTraceableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @internal
 */
final class IpTraceListener implements EventSubscriberInterface
{
    private $ipTraceableListener;

    public function __construct(IpTraceableListener $ipTraceableListener)
    {
        $this->ipTraceableListener = $ipTraceableListener;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $ip = $event->getRequest()->getClientIp();

        if (null !== $ip) {
            $this->ipTraceableListener->setIpValue($ip);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return array(
            KernelEvents::REQUEST => ['onKernelRequest', 500],
        );
    }
}
