<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Translatable\TranslatableListener;
use Stof\DoctrineExtensionsBundle\EventListener\LocaleListener;
use Stof\DoctrineExtensionsBundle\Tool\LocaleSynchronizer;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.translatable.class', TranslatableListener::class)
        ->set('stof_doctrine_extensions.event_listener.locale.class', LocaleListener::class) /** @phpstan-ignore classConstant.deprecatedClass */
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.translatable', param('stof_doctrine_extensions.listener.translatable.class'))
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
            ->call('setDefaultLocale', [param('stof_doctrine_extensions.default_locale')])
            ->call('setTranslatableLocale', [param('stof_doctrine_extensions.default_locale')])
            ->call('setTranslationFallback', [param('stof_doctrine_extensions.translation_fallback')])
            ->call('setPersistDefaultLocaleTranslation', [param('stof_doctrine_extensions.persist_default_translation')])
            ->call('setSkipOnLoad', [param('stof_doctrine_extensions.persist_default_translation')])

        ->set('stof_doctrine_extensions.tool.locale_synchronizer', LocaleSynchronizer::class)
            ->args([
                service('stof_doctrine_extensions.listener.translatable'),
            ])
            ->tag('kernel.locale_aware')

        ->set('stof_doctrine_extensions.event_listener.locale', param('stof_doctrine_extensions.event_listener.locale.class'))
            ->deprecate('stof/doctrine-extensions-bundle', '1.14', 'The "%service_id%" service is deprecated and will be removed in 2.0. The "stof_doctrine_extensions.tool.locale_synchronizer" service should be used to provide the locale instead.')
            ->args([
                service('stof_doctrine_extensions.listener.translatable'),
            ])
    ;
};
