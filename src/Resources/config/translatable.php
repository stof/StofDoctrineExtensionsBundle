<?php

declare(strict_types=1);

use Stof\DoctrineExtensionsBundle\EventListener\LocaleListener;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()
        ->set('stof_doctrine_extensions.listener.translatable.class', TranslatableListener::class)
        ->set('stof_doctrine_extensions.event_listener.locale.class', LocaleListener::class)
    ;

    $containerConfigurator->services()
        ->set('stof_doctrine_extensions.listener.translatable', '%stof_doctrine_extensions.listener.translatable.class%')
            ->call('setAnnotationReader', [new ReferenceConfigurator('annotation_reader')])
            ->call('setDefaultLocale', ['%stof_doctrine_extensions.default_locale%'])
            ->call('setTranslatableLocale', ['%stof_doctrine_extensions.default_locale%'])
            ->call('setTranslationFallback', ['%stof_doctrine_extensions.translation_fallback%'])
            ->call('setPersistDefaultLocaleTranslation', ['%stof_doctrine_extensions.persist_default_translation%'])
            ->call('setSkipOnLoad', ['%stof_doctrine_extensions.skip_translation_on_load%'])

        ->set('stof_doctrine_extensions.event_listener.locale', '%stof_doctrine_extensions.event_listener.locale.class%')
            ->tag('kernel.event_subscriber')
            ->args([
                new ReferenceConfigurator('stof_doctrine_extensions.listener.translatable'),
            ])
    ;
};
