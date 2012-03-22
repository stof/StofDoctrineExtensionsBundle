<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StofDoctrineExtensionsExtension extends Extension
{
    private $entityManagers   = array();
    private $documentManagers = array();
    private $filters = array();

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
                if ('soft_deleteable' === $ext) {
                    $this->filters[] = array(
                        'name' => $name,
                        'ext' => $ext,
                        'extClass' => $config['class']['soft_deleteable_filter'],
                    );
                }

                $listener = sprintf('stof_doctrine_extensions.listener.%s', $ext);
                if ($enabled && $container->hasDefinition($listener)) {
                    if ('translatable' === $ext) {
                        $useTranslatable = true;
                    } elseif ('loggable' === $ext) {
                        $useLoggable = true;
                    }
                    $definition = $container->getDefinition($listener);
                    $definition->addTag('doctrine.event_subscriber', array('connection' => $name));
                }
            }

            $this->entityManagers[$name] = $listeners;
        }

        foreach ($config['mongodb'] as $name => $listeners) {
            foreach ($listeners as $ext => $enabled) {
                $listener = sprintf('stof_doctrine_extensions.listener.%s', $ext);
                if ($enabled && $container->hasDefinition($listener)) {
                    if ('translatable' === $ext) {
                        $useTranslatable = true;
                    } elseif ('loggable' === $ext) {
                        $useLoggable = true;
                    }
                    $definition = $container->getDefinition($listener);
                    $definition->addTag('doctrine.odm.mongodb.event_subscriber', array('connection' => $name));
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

        foreach ($config['class'] as $extension => $class) {
            $container->setParameter(sprintf('stof_doctrine_extensions.listener.%s.class', $extension), $class);
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

    public function configFilters(ContainerBuilder $container)
    {
        foreach ($this->filters as $filterConfig) {
            $ormConfDef = $container->getDefinition(sprintf('doctrine.orm.%s_configuration', $filterConfig['name']));
            $ormConfDef->addMethodCall('addFilter', array($filterConfig['ext'], $filterConfig['extClass']));
        }
    }
}
