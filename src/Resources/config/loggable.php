<?php

declare(strict_types=1);

use Stof\DoctrineExtensionsBundle\EventListener\LoggerListener;
use Gedmo\Loggable\LoggableListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()
        ->set('stof_doctrine_extensions.listener.loggable.class', LoggableListener::class)
        ->set('stof_doctrine_extensions.event_listener.logger.class', LoggerListener::class)
    ;

    $containerConfigurator->services()
        ->set('stof_doctrine_extensions.listener.loggable', '%stof_doctrine_extensions.listener.loggable.class%')
            ->call('setAnnotationReader', [new ReferenceConfigurator('annotation_reader')])
            ->tag('stof_doctrine_extensions.listener')

        ->set('stof_doctrine_extensions.event_listener.logger', '%stof_doctrine_extensions.event_listener.logger.class%')
            ->tag('kernel.event_subscriber')
            ->args([
                new ReferenceConfigurator('stof_doctrine_extensions.listener.loggable'),
                (new ReferenceConfigurator('security.token_storage'))->nullOnInvalid(),
                (new ReferenceConfigurator('security.authorization_checker'))->nullOnInvalid(),
            ])
    ;
};
