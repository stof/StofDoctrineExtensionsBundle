<?php

declare(strict_types=1);

use Gedmo\ReferenceIntegrity\ReferenceIntegrityListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()
        ->set('stof_doctrine_extensions.listener.reference_integrity.class', ReferenceIntegrityListener::class)
    ;

    $services = $containerConfigurator->services();

    $services
        ->set('stof_doctrine_extensions.listener.reference_integrity', '%stof_doctrine_extensions.listener.reference_integrity.class%')
            ->call('setAnnotationReader', [new ReferenceConfigurator('annotation_reader')])
            ->tag('stof_doctrine_extensions.listener')
    ;
};
