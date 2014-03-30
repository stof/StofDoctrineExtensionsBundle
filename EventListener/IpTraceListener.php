<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;

use Gedmo\IpTraceable\IpTraceableListener;

/**
 * IpTraceableListener
 *
 * @author Pierre-Charles Bertineau <pc.bertineau@alterphp.com>
 */
class BlameListener implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var IpTraceableListener
     */
    private $ipTraceableListener;

    public function __construct(IpTraceableListener $ipTraceableListener, Request $request = null, )
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

        // TODO: New in 2.3, trusted proxies are defined in a parameter (kernel.trusted_proxies)
        // $this->request->setTrustedProxies(array('127.0.0.1'));

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
