<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\ReferenceIntegrity\ReferenceIntegrityListener;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.reference_integrity.class', ReferenceIntegrityListener::class)
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.reference_integrity', param('stof_doctrine_extensions.listener.reference_integrity.class'))
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
    ;
};
