<?php

declare(strict_types=1);

use Gedmo\Sluggable\SluggableListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()
        ->set('stof_doctrine_extensions.listener.sluggable.class', SluggableListener::class)
    ;

    $containerConfigurator->services()
        ->set('stof_doctrine_extensions.listener.sluggable', '%stof_doctrine_extensions.listener.sluggable.class%')
            ->call('setAnnotationReader', [new ReferenceConfigurator('annotation_reader')])
            ->tag('stof_doctrine_extensions.listener')
    ;
};
