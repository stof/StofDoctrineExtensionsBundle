<?php

namespace Stof\DoctrineExtensionsBundle\ORM;

use Gedmo\Loggable\LoggableListener as BaseLoggableListener;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * LoggableListener
 *
 * @author jules BOUSSEKEYT
 * @author Christophe Coevoet <stof@notk.org>
 */
class LoggableListener extends BaseLoggableListener
{
    protected $defaultLogEntryEntity = 'Stof\DoctrineExtensionsBundle\Entity\LogEntry';

    /**
     * Set the username from the security context by listening on core.request
     *
     * @param GetResponseEvent $event
     */
    public function onCoreRequest(GetResponseEvent $event)
    {
        $securityContext = $event->getKernel()->getContainer()->get('security.context', ContainerInterface::NULL_ON_INVALID_REFERENCE);
        if (null !== $securityContext && null !== $securityContext->getToken() && $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->setUsername($this->securityContext->getToken()->getUsername());
        }
    }
}
