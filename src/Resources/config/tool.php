<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Stof\DoctrineExtensionsBundle\Tool\RequestStackIpAddressProvider;
use Stof\DoctrineExtensionsBundle\Tool\TokenStorageActorProvider;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('stof_doctrine_extensions.tool.actor_provider', TokenStorageActorProvider::class)
            ->args([
                service('security.token_storage')->nullOnInvalid(),
                service('security.authorization_checker')->nullOnInvalid(),
            ])

        ->set('stof_doctrine_extensions.tool.ip_address_provider', RequestStackIpAddressProvider::class)
            ->args([
                service('request_stack')->nullOnInvalid(),
            ])
    ;
};
