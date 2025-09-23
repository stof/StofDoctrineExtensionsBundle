<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\SoftDeleteable\SoftDeleteableListener;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.softdeleteable.class', SoftDeleteableListener::class)
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.softdeleteable', param('stof_doctrine_extensions.listener.softdeleteable.class'))
            ->args([abstract_arg('Set in the extension')])
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setClock', [service('clock')->ignoreOnInvalid()])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
    ;
};
