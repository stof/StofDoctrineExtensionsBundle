<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Timestampable\TimestampableListener;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.timestampable.class', TimestampableListener::class)
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.timestampable', param('stof_doctrine_extensions.listener.timestampable.class'))
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setClock', [service('clock')->ignoreOnInvalid()])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
    ;
};
