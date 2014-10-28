<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use Gedmo\IpTraceable\IpTraceableListener;

/**
 * IpTraceListener
 *
 * @link https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/ip_traceable.md
 */
class IpTraceListener implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var IpTraceableListener
     */
    private $ipTraceableListener;

    public function __construct(IpTraceableListener $ipTraceableListener, Request $request = null)
    {
        $this->ipTraceableListener = $ipTraceableListener;
        $this->request = $request;
    }

    /**
     * Set the username from the security context by listening on core.request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (null === $this->request) {
            return;
        }

        $ip = $this->request->getClientIp();

        if (null !== $ip) {
            $this->ipTraceableListener->setIpValue($ip);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}
