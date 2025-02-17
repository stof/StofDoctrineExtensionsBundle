<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Service\ResetInterface;

use Gedmo\Blameable\BlameableListener;

/**
 * Sets the username from the security context by listening on kernel.request
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class BlameListener implements EventSubscriberInterface, ResetInterface
{
    private ?AuthorizationCheckerInterface $authorizationChecker;
    private ?TokenStorageInterface $tokenStorage;
    private BlameableListener $blameableListener;

    public function __construct(BlameableListener $blameableListener, ?TokenStorageInterface $tokenStorage = null, ?AuthorizationCheckerInterface $authorizationChecker = null)
    {
        $this->blameableListener = $blameableListener;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @internal
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
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

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }

    public function reset(): void
    {
        $this->blameableListener->setUserValue(null);
    }
}
