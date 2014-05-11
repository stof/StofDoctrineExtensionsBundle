<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Gedmo\IpTraceable\IpTraceableListener;

/**
 * IpTraceableListener
 *
 * @author Pierre-Charles Bertineau <pc.bertineau@alterphp.com>
 */
class IpTraceListener implements EventSubscriberInterface
{
    /**
     * @var IpTraceableListener
     */
    private $ipTraceableListener;

    public function __construct(IpTraceableListener $ipTraceableListener)
    {
        $this->ipTraceableListener = $ipTraceableListener;
    }

    /**
     * Set client IP from event Request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            $ip = $event->getRequest()->getClientIp();

            if (null !== $ip) {
                $this->ipTraceableListener->setIpValue($ip);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}
