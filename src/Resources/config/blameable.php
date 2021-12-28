<?php

declare(strict_types=1);

use Stof\DoctrineExtensionsBundle\EventListener\BlameListener;
use Gedmo\Blameable\BlameableListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()
        ->set('stof_doctrine_extensions.listener.blameable.class', BlameableListener::class)
        ->set('stof_doctrine_extensions.event_listener.blame.class', BlameListener::class)
    ;

    $containerConfigurator->services()
        ->set('stof_doctrine_extensions.listener.blameable', '%stof_doctrine_extensions.listener.blameable.class%')
            ->call('setAnnotationReader', [new ReferenceConfigurator('annotation_reader')])

        ->set('stof_doctrine_extensions.event_listener.blame', '%stof_doctrine_extensions.event_listener.blame.class%')
            ->tag('kernel.event_subscriber')
            ->args([
                new ReferenceConfigurator('stof_doctrine_extensions.listener.blameable'),
                (new ReferenceConfigurator('security.token_storage'))->nullOnInvalid(),
                (new ReferenceConfigurator('security.authorization_checker'))->nullOnInvalid(),
            ])
    ;
};
