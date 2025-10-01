<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Gedmo\Uploadable\UploadableListener;
use Stof\DoctrineExtensionsBundle\Uploadable\MimeTypeGuesserAdapter;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadedFileInfo;
use Stof\DoctrineExtensionsBundle\Uploadable\ValidatorConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('stof_doctrine_extensions.listener.uploadable.class', UploadableListener::class)
        ->set('stof_doctrine_extensions.listener.uploadable.manager.class', UploadableManager::class)
        ->set('stof_doctrine_extensions.uploadable.mime_type_guesser.class', MimeTypeGuesserAdapter::class)
        ->set('stof_doctrine_extensions.uploadable.default_file_info.class', UploadedFileInfo::class)
    ;

    $container->services()
        ->set('stof_doctrine_extensions.listener.uploadable', param('stof_doctrine_extensions.listener.uploadable.class'))
            ->configurator([service('stof_doctrine_extensions.uploadable.configurator'), 'configure'])
            ->args([
                service('stof_doctrine_extensions.uploadable.mime_type_guesser'),
            ])
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
            ->call('setDefaultFileInfoClass', [param('stof_doctrine_extensions.uploadable.default_file_info.class')])

        ->set('stof_doctrine_extensions.uploadable.mime_type_guesser', param('stof_doctrine_extensions.uploadable.mime_type_guesser.class'))

        ->set('stof_doctrine_extensions.uploadable.manager', param('stof_doctrine_extensions.uploadable.manager.class'))
            ->args([
                service('stof_doctrine_extensions.listener.uploadable'),
                param('stof_doctrine_extensions.uploadable.default_file_info.class')
            ])
            ->call('setCacheItemPool', [service('stof_doctrine_extensions.metadata_cache')])
            ->call('setAnnotationReader', [service('.stof_doctrine_extensions.reader')->ignoreOnInvalid()])
            ->call('setDefaultFileInfoClass', [param('stof_doctrine_extensions.uploadable.default_file_info.class')])
        ->alias(UploadableManager::class, 'stof_doctrine_extensions.uploadable_manager')

        ->set('stof_doctrine_extensions.uploadable.configurator', ValidatorConfigurator::class)
            ->args([
                param('stof_doctrine_extensions.uploadable.validate_writable_directory')
            ])
    ;
};
