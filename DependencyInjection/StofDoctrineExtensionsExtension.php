<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StofDoctrineExtensionsExtension extends Extension
{
    protected $entityManagers   = array();
    protected $documentManagers = array();

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->process($configuration->getConfigTree(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        if ($config['orm']) {
            $loader->load('orm.xml');

            foreach ($config['orm'] as $name => $listeners) {
                foreach ($listeners as $ext => $enabled) {
                    $listener = sprintf('stof_doctrine_extensions.orm.listener.%s', $ext);
                    if ($enabled && $container->hasDefinition($listener)) {
                        $definition = $container->getDefinition($listener);
                        $definition->addTag(sprintf('doctrine.dbal.%s_event_subscriber', $name));
                        if ('loggable' === $ext) {
                            $definition->addTag('kernel.listener', array('event' => 'onCoreRequest', 'priority' => -150)); // Executed after the security one.
                        }
                    }
                }

                $this->entityManagers[$name] = $listeners;
            }
        }

        if ($config['mongodb']) {
            $loader->load('mongodb.xml');

            foreach ($config['mongodb'] as $name => $listeners) {
                foreach ($listeners as $ext => $enabled) {
                    $listener = sprintf('stof_doctrine_extensions.odm.mongodb.listener.%s', $ext);
                    if ($enabled && $container->hasDefinition($listener)) {
                        $definition = $container->getDefinition($listener);
                        $definition->addTag(sprintf('doctrine.odm.mongodb.%s_event_subscriber', $name));
                        if ('loggable' === $ext) {
                            $definition->addTag('kernel.listener', array('event' => 'onCoreRequest', 'priority' => -150)); // Executed after the security one.
                        }
                    }
                }
                $this->documentManagers[$name] = $listeners;
            }
        }

        if (isset($config['class'])) {
            $this->remapParametersNamespaces($config['class'], $container, array(
                'orm'       => 'stof_doctrine_extensions.orm.listener.%s.class',
                'mongodb'   => 'stof_doctrine_extensions.odm.mongodb.listener.%s.class',
            ));
        }
    }

    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (isset($config[$name])) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }

    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!isset($config[$ns])) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    if (null !== $value) {
                        $container->setParameter(sprintf($map, $name), $value);
                    }
                }
            }
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

    public function getNamespace()
    {
        return 'http://symfony.com/schema/dic/stof_doctrine_extensions';
    }
}
