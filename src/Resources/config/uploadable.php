<?php

declare(strict_types=1);

use Stof\DoctrineExtensionsBundle\Uploadable\ValidatorConfigurator;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadedFileInfo;
use Stof\DoctrineExtensionsBundle\Uploadable\MimeTypeGuesserAdapter;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadableManager;
use Gedmo\Uploadable\UploadableListener;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ReferenceConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->parameters()
        ->set('stof_doctrine_extensions.listener.uploadable.class', UploadableListener::class)
        ->set('stof_doctrine_extensions.uploadable.manager.class', UploadableManager::class)
        ->set('stof_doctrine_extensions.uploadable.mime_type_guesser.class', MimeTypeGuesserAdapter::class)
        ->set('stof_doctrine_extensions.uploadable.default_file_info.class', UploadedFileInfo::class)
    ;

    $containerConfigurator->services()
        ->set('stof_doctrine_extensions.listener.uploadable', '%stof_doctrine_extensions.listener.uploadable.class%')
            ->args([
                new ReferenceConfigurator('stof_doctrine_extensions.uploadable.mime_type_guesser'),
            ])
            ->call('setAnnotationReader', [new ReferenceConfigurator('annotation_reader')])
            ->call('setDefaultFileInfoClass', ['%stof_doctrine_extensions.uploadable.default_file_info.class%'])
            ->factory([new ReferenceConfigurator('stof_doctrine_extensions.uploadable.configurator'), 'configure'])
            ->tag('stof_doctrine_extensions.listener')

        ->set('stof_doctrine_extensions.uploadable.mime_type_guesser', '%stof_doctrine_extensions.uploadable.mime_type_guesser.class%')

        ->set('stof_doctrine_extensions.uploadable.manager', '%stof_doctrine_extensions.uploadable.manager.class%')
            ->public()
            ->args([
                new ReferenceConfigurator('stof_doctrine_extensions.listener.uploadable'),
                '%stof_doctrine_extensions.uploadable.default_file_info.class%',
            ])

        ->set('stof_doctrine_extensions.uploadable.configurator', ValidatorConfigurator::class)
            ->args([
                '%stof_doctrine_extensions.uploadable.validate_writable_directory%',
            ])

        ->alias(UploadableManager::class, 'stof_doctrine_extensions.uploadable.manager')
    ;
};
