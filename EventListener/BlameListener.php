<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

use Gedmo\Blameable\BlameableListener;

/**
 * BlameableListener
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class BlameListener implements EventSubscriberInterface
{
    private $authorizationChecker;
    private $tokenStorage;

    /**
     * @var BlameableListener
     */
    private $blameableListener;

    public function __construct(BlameableListener $blameableListener, $tokenStorage = null, AuthorizationCheckerInterface $authorizationChecker = null)
    {
        $this->blameableListener = $blameableListener;

        // BC layer for Symfony 2.5 and older
        if ($tokenStorage instanceof SecurityContextInterface) {
            $this->tokenStorage = $this->authorizationChecker = $tokenStorage;

            return;
        }

        if (null !== $tokenStorage && !$tokenStorage instanceof TokenStorageInterface) {
            throw new \InvalidArgumentException(sprintf('The second argument of the %s constructor should be a Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface or a Symfony\Component\Security\Core\SecurityContextInterface or null.', __CLASS__));
        }

        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * Set the username from the security context by listening on core.request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (null !== $token && $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->blameableListener->setUserValue($token->getUser());
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}
