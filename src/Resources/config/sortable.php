<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Sortable\SortableListener;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.sortable.class', SortableListener::class)
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.sortable', param('stof_doctrine_extensions.listener.sortable.class'))
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
    ;
};
