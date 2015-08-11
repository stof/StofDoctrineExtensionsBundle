<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StofDoctrineExtensionsExtension extends Extension
{
    private $entityManagers   = array();
    private $documentManagers = array();

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('listeners.xml');

        $uploadableConfig = $config['uploadable'];

        $container->setParameter('stof_doctrine_extensions.default_locale', $config['default_locale']);
        $container->setParameter('stof_doctrine_extensions.default_file_path', $uploadableConfig['default_file_path']);
        $container->setParameter('stof_doctrine_extensions.translation_fallback', $config['translation_fallback']);
        $container->setParameter('stof_doctrine_extensions.persist_default_translation', $config['persist_default_translation']);
        $container->setParameter('stof_doctrine_extensions.skip_translation_on_load', $config['skip_translation_on_load']);

        $useTranslatable = false;
        $useLoggable = false;
        $useBlameable = false;

        foreach ($config['orm'] as $name => $listeners) {
            foreach ($listeners as $ext => $enabled) {
                $listener = sprintf('stof_doctrine_extensions.listener.%s', $ext);
                if ($enabled && $container->hasDefinition($listener)) {
                    $attributes = array('connection' => $name);
                    if ('translatable' === $ext) {
                        $useTranslatable = true;
                        // the translatable listener must be registered after others to work with them properly
                        $attributes['priority'] = -10;
                    } elseif ('loggable' === $ext) {
                        $useLoggable = true;
                    } elseif ('blameable' === $ext) {
                        $useBlameable = true;
                    } elseif ('uploadable' === $ext) {
                        $attributes['priority'] = -5;
                    }
                    $definition = $container->getDefinition($listener);
                    $definition->addTag('doctrine.event_subscriber', $attributes);
                }
            }

            $this->entityManagers[$name] = $listeners;
        }

        foreach ($config['mongodb'] as $name => $listeners) {
            foreach ($listeners as $ext => $enabled) {
                $listener = sprintf('stof_doctrine_extensions.listener.%s', $ext);
                if ($enabled && $container->hasDefinition($listener)) {
                    $attributes = array('connection' => $name);
                    if ('translatable' === $ext) {
                        $useTranslatable = true;
                        // the translatable listener must be registered after others to work with them properly
                        $attributes['priority'] = -10;
                    } elseif ('loggable' === $ext) {
                        $useLoggable = true;
                    } elseif ('blameable' === $ext) {
                        $useBlameable = true;
                    }
                    $definition = $container->getDefinition($listener);
                    $definition->addTag('doctrine_mongodb.odm.event_subscriber', $attributes);
                }
            }
            $this->documentManagers[$name] = $listeners;
        }

        if ($useTranslatable) {
            $container->getDefinition('stof_doctrine_extensions.event_listener.locale')
                ->setPublic(true)
                ->addTag('kernel.event_subscriber');
        }
        if ($useLoggable) {
            $container->getDefinition('stof_doctrine_extensions.event_listener.logger')
                ->setPublic(true)
                ->addTag('kernel.event_subscriber');
        }
        if ($useBlameable) {
            $container->getDefinition('stof_doctrine_extensions.event_listener.blame')
                ->setPublic(true)
                ->addTag('kernel.event_subscriber');
        }

        if ($uploadableConfig['default_file_path']) {
            $container->getDefinition('stof_doctrine_extensions.listener.uploadable')
                ->addMethodCall('setDefaultPath', array($uploadableConfig['default_file_path']));
        }

        // Default FileInfoInterface class
        $container->setParameter('stof_doctrine_extensions.uploadable.default_file_info.class', $uploadableConfig['default_file_info_class']);

        $container->setParameter(
            'stof_doctrine_extensions.uploadable.validate_writable_directory',
            $uploadableConfig['validate_writable_directory']
        );

        if ($uploadableConfig['mime_type_guesser_class']) {
            if (!class_exists($uploadableConfig['mime_type_guesser_class'])) {
                $msg = 'Class "%s" configured to use as the mime type guesser in the Uploadable extension does not exist.';

                throw new \InvalidArgumentException(sprintf($msg, $uploadableConfig['mime_type_guesser_class']));
            }

            $container->setParameter(
                'stof_doctrine_extensions.uploadable.mime_type_guesser.class',
                $uploadableConfig['mime_type_guesser_class']
            );
        }

        foreach ($config['class'] as $listener => $class) {
            $container->setParameter(sprintf('stof_doctrine_extensions.listener.%s.class', $listener), $class);
        }
    }

    public function configValidate(ContainerBuilder $container)
    {
        foreach (array_keys($this->entityManagers) as $name) {
            if (!$container->hasDefinition(sprintf('doctrine.dbal.%s_connection', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: DBAL connection "%s" not found', $this->getAlias(), $name));
            }
        }

        foreach (array_keys($this->documentManagers) as $name) {
            if (!$container->hasDefinition(sprintf('doctrine_mongodb.odm.%s_document_manager', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: document manager "%s" not found', $this->getAlias(), $name));
            }
        }
    }
}
