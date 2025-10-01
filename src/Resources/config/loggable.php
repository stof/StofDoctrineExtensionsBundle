<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Loggable\LoggableListener;
use Stof\DoctrineExtensionsBundle\EventListener\LoggerListener;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.loggable.class', LoggableListener::class)
        ->set('stof_doctrine_extensions.event_listener.logger.class', LoggerListener::class) /** @phpstan-ignore classConstant.deprecatedClass */
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.loggable', param('stof_doctrine_extensions.listener.loggable.class'))
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
            ->call('setActorProvider', [service('stof_doctrine_extensions.tool.actor_provider')])

        ->set('stof_doctrine_extensions.event_listener.logger', param('stof_doctrine_extensions.event_listener.logger.class'))
            ->deprecate('stof/doctrine-extensions-bundle', '1.14', 'The "%service_id%" service is deprecated and will be removed in 2.0. The "stof_doctrine_extensions.tool.actor_provider" service should be used to provide the user instead.')
            ->args([
                service('stof_doctrine_extensions.listener.loggable'),
                service('security.token_storage')->nullOnInvalid(),
                service('security.authorization_checker')->nullOnInvalid(),
            ])
    ;
};
