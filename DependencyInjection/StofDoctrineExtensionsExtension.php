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

        $container->setParameter('stof_doctrine_extensions.default_locale', $config['default_locale']);
        $container->setParameter('stof_doctrine_extensions.translation_fallback', $config['translation_fallback']);
        $container->setParameter('stof_doctrine_extensions.persist_default_translation', $config['persist_default_translation']);

        $useTranslatable = false;
        $useLoggable = false;

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
                    }
                    $definition = $container->getDefinition($listener);
                    $definition->addTag('doctrine.odm.mongodb.event_subscriber', $attributes);
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
            if (!$container->hasDefinition(sprintf('doctrine.odm.mongodb.%s_document_manager', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: document manager "%s" not found', $this->getAlias(), $name));
            }
        }
    }
}
