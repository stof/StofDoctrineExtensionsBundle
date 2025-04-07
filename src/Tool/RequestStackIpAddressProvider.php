<?php

namespace Stof\DoctrineExtensionsBundle\Tool;

use Gedmo\Tool\IpAddressProviderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides an IP address for the extensions using an IP address reference.
 *
 * @internal
 */
final class RequestStackIpAddressProvider implements IpAddressProviderInterface
{
    private ?RequestStack $requestStack;

    public function __construct(?RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getAddress(): ?string
    {
        if (null === $this->requestStack) {
            return null;
        }

        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return null;
        }

        return $request->getClientIp();
    }
}
