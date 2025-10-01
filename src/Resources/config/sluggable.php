<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Sluggable\SluggableListener;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.sluggable.class', SluggableListener::class)
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.sluggable', param('stof_doctrine_extensions.listener.sluggable.class'))
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
    ;
};
