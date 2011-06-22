<?php

namespace Stof\DoctrineExtensionsBundle\Listener;

use Gedmo\Loggable\LoggableListener as BaseLoggableListener;
use Gedmo\Loggable\Mapping\Event\LoggableAdapter;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * LoggableListener
 *
 * @author jules BOUSSEKEYT
 * @author Christophe Coevoet <stof@notk.org>
 */
class LoggableListener extends BaseLoggableListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Set the username from the security context by listening on core.request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $securityContext = $this->container->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if (null !== $securityContext && null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->setUsername($securityContext->getToken()->getUsername());
        }
    }

    protected function getLogEntryClass(LoggableAdapter $ea, $class)
    {
        $class = parent::getLogEntryClass($ea, $class);

        if ($class === 'Gedmo\\Loggable\\Entity\\LogEntry') {
            return 'Stof\\DoctrineExtensionsBundle\\Entity\\LogEntry';
        } elseif ($class === 'Gedmo\\Loggable\\Document\\LogEntry') {
            return 'Stof\\DoctrineExtensionsBundle\\Document\\LogEntry';
        }

        return $class;
    }
}
