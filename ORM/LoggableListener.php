<?php

namespace Stof\DoctrineExtensionsBundle\ORM;

use Gedmo\Loggable\LoggableListener as BaseLoggableListener;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

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
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @param SecurityContextInterface $securityContext
     */
    public function setSecurityContext(SecurityContextInterface $securityContext = null) {
        $this->securityContext = $securityContext;
    }

    /**
     * Set the username from the security context by listening on core.request
     *
     * @param GetResponseEvent $event
     */
    public function onCoreRequest(GetResponseEvent $event)
    {
        if (null !== $this->securityContext && null !== $this->securityContext->getToken() && $this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->setUsername($this->securityContext->getToken()->getUsername());
        }
    }
}
