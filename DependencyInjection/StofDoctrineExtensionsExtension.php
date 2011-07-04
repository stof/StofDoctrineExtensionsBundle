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

        foreach ($config['orm'] as $name => $listeners) {
            foreach ($listeners as $ext => $enabled) {
                $listener = sprintf('stof_doctrine_extensions.listener.%s', $ext);
                if ($enabled && $container->hasDefinition($listener)) {
                    $definition = $container->getDefinition($listener);
                    $definition->addTag('doctrine.event_subscriber', array('connection' => $name));
                    if ('loggable' === $ext) {
                        $definition->addTag('kernel.event_listener', array('event' => 'kernel.request', 'method' => 'onKernelRequest')); // Executed after the security one.
                    }
                }
            }

            $this->entityManagers[$name] = $listeners;
        }

        foreach ($config['mongodb'] as $name => $listeners) {
            foreach ($listeners as $ext => $enabled) {
                $listener = sprintf('stof_doctrine_extensions.listener.%s', $ext);
                if ($enabled && $container->hasDefinition($listener)) {
                    $definition = $container->getDefinition($listener);
                    $definition->addTag(sprintf('doctrine.odm.mongodb.%s_event_subscriber', $name));
                    if ('loggable' === $ext) {
                        $definition->addTag('kernel.event_listener', array('event' => 'kernel.request', 'method' => 'onKernelRequest')); // Executed after the security one.
                    }
                }
            }
            $this->documentManagers[$name] = $listeners;
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
