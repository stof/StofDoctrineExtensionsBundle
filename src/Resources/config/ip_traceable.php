<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\IpTraceable\IpTraceableListener;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('stof_doctrine_extensions.listener.ip_traceable', IpTraceableListener::class)
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
            ->call('setIpAddressProvider', [service('stof_doctrine_extensions.tool.ip_address_provider')])
    ;
};
