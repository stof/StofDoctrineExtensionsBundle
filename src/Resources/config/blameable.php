<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Blameable\BlameableListener;
use Stof\DoctrineExtensionsBundle\EventListener\BlameListener;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.blameable.class', BlameableListener::class)
        ->set('stof_doctrine_extensions.event_listener.blame.class', BlameListener::class) /** @phpstan-ignore classConstant.deprecatedClass */
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.blameable', param('stof_doctrine_extensions.listener.blameable.class'))
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
            ->call('setActorProvider', [service('stof_doctrine_extensions.tool.actor_provider')])

        ->set('stof_doctrine_extensions.event_listener.blame', param('stof_doctrine_extensions.event_listener.blame.class'))
            ->deprecate('stof/doctrine-extensions-bundle', '1.14', 'The "%service_id%" service is deprecated and will be removed in 2.0. The "stof_doctrine_extensions.tool.actor_provider" service should be used to provide the user instead.')
            ->args([
                service('stof_doctrine_extensions.listener.blameable'),
                service('security.token_storage')->nullOnInvalid(),
                service('security.authorization_checker')->nullOnInvalid(),
            ])
    ;
};
