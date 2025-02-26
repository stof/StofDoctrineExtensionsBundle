<?php

namespace Stof\DoctrineExtensionsBundle\Tool;

use Gedmo\Tool\ActorProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Provides an actor for the extensions using the token storage.
 *
 * @internal
 */
final class TokenStorageActorProvider implements ActorProviderInterface
{
    private ?TokenStorageInterface $tokenStorage;
    private ?AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(?TokenStorageInterface $tokenStorage = null, ?AuthorizationCheckerInterface $authorizationChecker = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getActor(): ?UserInterface
    {
        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            return null;
        }

        $token = $this->tokenStorage->getToken();

        if (null === $token || !$this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return null;
        }

        return $token->getUser();
    }
}
