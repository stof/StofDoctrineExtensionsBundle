<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineExtensionsExtension extends Extension
{
    protected $entityManagers   = array();
    protected $documentManagers = array();

    public function configLoad(array $configs, ContainerBuilder $container)
    {

        $defaultListeners = array (
            'tree' => true,
            'timestampable' => true,
            'translatable' => true,
            'sluggable' => true,
        );
        $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');

        foreach ($configs as $config) {
            if (isset($config['orm'])) {
                $loader->load('orm.xml');

                $emConfig = $config['orm'];
                foreach ($emConfig as $name => $listeners) {
                    if (null === $listeners){
                        $listeners = array ();
                    }
                    if (isset($listeners['id'])) {
                        $name = $listeners['id'];
                        unset ($listeners['id']);
                    }
                    $this->entityManagers[$name] = array_merge($defaultListeners, $listeners);
                }
            }

            if (isset($config['mongodb'])) {
                $loader->load('mongodb.xml');

                $mongodbConfig = $config['mongodb'];
                foreach ($mongodbConfig as $name => $listeners) {
                    if (null === $listeners) {
                        $listeners = array ();
                    }
                    if (isset($listeners['id'])) {
                        $name = $listeners['id'];
                        unset ($listeners['id']);
                    }
                    $this->documentManagers[$name] = array_merge($defaultListeners, $listeners);
                }
            }

            if (isset($config['class'])) {
                $this->remapParametersNamespaces($config['class'], $container, array(
                    'orm'       => 'stof_doctrine_extensions.orm.listener.%s.class',
                    'mongodb'   => 'stof_doctrine_extensions.odm.mongodb.listener.%s.class',
                ));
            }
        }

        foreach ($this->entityManagers as $name => $listeners) {
            foreach ($listeners as $ext => $enabled) {
                $listener = sprintf('stof_doctrine_extensions.orm.listener.%s', $ext);
                if ($enabled && $container->hasDefinition($listener)) {
                    $container->getDefinition($listener)
                            ->addTag(sprintf('doctrine.dbal.%s_event_subscriber', $name));
                }
            }
        }

        foreach ($this->documentManagers as $name => $listeners) {
            foreach ($listeners as $ext => $enabled) {
                $listener = sprintf('stof_doctrine_extensions.odm.mongodb.listener.%s', $ext);
                if ($enabled && $container->hasDefinition($listener)) {
                    $container->getDefinition($listener)
                            ->addTag(sprintf('doctrine.odm.mongodb.%s_event_subscriber', $name));
                }
            }
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

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return null;
    }

    public function getNamespace()
    {
        return 'http://www.symfony-project.org/schema/dic/stof_doctrine_extensions';
    }

    public function getAlias()
    {
        return 'stof_doctrine_extensions';
    }
}
