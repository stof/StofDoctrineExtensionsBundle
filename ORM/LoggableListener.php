<?php

namespace Stof\DoctrineExtensionsBundle\ORM;

use Gedmo\Loggable\LoggableListener as BaseLoggableListener,
    Symfony\Component\Security\Core\SecurityContext;

/**
 * LoggableListener
 *
 * @author jules BOUSSEKEYT
 */
class LoggableListener extends BaseLoggableListener
{
    protected $defaultLogEntryEntity = 'Stof\DoctrineExtensionsBundle\Entity\LogEntry';

    protected $anonymousUsername = 'anonymous';

    /**
     * Set the translation listener locale from the session.
     *
     * @param Session $session
     * @return void
     */
    public function setUsernameFromSecurity(SecurityContext $security)
    {
        if (null !== $security->getToken() || !$security->vote('IS_AUTHENTICATED_ANONYMOUSLY')) {
            $this->setUsername($security->getToken()->getUser()->getUsername());
        }
    }
}
