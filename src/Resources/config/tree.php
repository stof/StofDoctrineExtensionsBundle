<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Tree\TreeListener;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.tree.class', TreeListener::class)
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.tree', param('stof_doctrine_extensions.listener.tree.class'))
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
    ;
};
