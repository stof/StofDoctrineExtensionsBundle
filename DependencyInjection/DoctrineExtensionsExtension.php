<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineExtensionsExtension extends Extension
{
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

                $entity_managers = $container->getParameter('stof_doctrine_extensions.orm.entity_managers');
                $emConfig = $config['orm'];
                foreach ($emConfig as $name => $listeners) {
                    if (null === $listeners){
                        $listeners = array ();
                    }
                    if (isset($listeners['id'])) {
                        $name = $listeners['id'];
                        unset ($listeners['id']);
                    }
                    $entity_managers[$name] = array_merge($defaultListeners, $listeners);
                }
                $container->setParameter('stof_doctrine_extensions.orm.entity_managers', $entity_managers);
            }

            if (isset($config['mongodb'])) {
                $loader->load('mongodb.xml');

                $document_managers = $container->getParameter('stof_doctrine_extensions.odm.mongodb.document_managers');
                $mongodbConfig = $config['mongodb'];
                foreach ($mongodbConfig as $name => $listeners) {
                    if (null === $listeners) {
                        $listeners = array ();
                    }
                    if (isset($listeners['id'])) {
                        $name = $listeners['id'];
                        unset ($listeners['id']);
                    }
                    $document_managers[$name] = array_merge($defaultListeners, $listeners);
                }
                $container->setParameter('stof_doctrine_extensions.odm.mongodb.document_managers', $document_managers);
            }

            if (isset($config['class'])) {
                $this->remapParametersNamespaces($config['class'], $container, array(
                    'orm'       => 'stof_doctrine_extensions.orm.listener.%s.class',
                    'mongodb'   => 'stof_doctrine_extensions.odm.mongodb.listener.%s.class',
                ));
            }
        }

        if ($container->hasParameter('stof_doctrine_extensions.orm.entity_managers')) {
            foreach ($container->getParameter('stof_doctrine_extensions.orm.entity_managers') as $manager => $listeners) {
                foreach ($listeners as $ext => $enabled) {
                    $listener = sprintf('stof_doctrine_extensions.orm.listener.%s', $ext);
                    if ($enabled && $container->hasDefinition($listener)) {
                        $container->getDefinition($listener)
                            ->addTag(sprintf('doctrine.dbal.%s_event_subscriber', $manager));
                    }
                }
            }
        }

        if ($container->hasParameter('stof_doctrine_extensions.odm.mongodb.document_managers')) {
            foreach ($container->getParameter('stof_doctrine_extensions.odm.mongodb.document_managers') as $manager => $listeners) {
                foreach ($listeners as $ext => $enabled) {
                    $listener = sprintf('stof_doctrine_extensions.odm.mongodb.listener.%s', $ext);
                    if ($enabled && $container->hasDefinition($listener)) {
                        $container->getDefinition($listener)
                            ->addTag(sprintf('doctrine.odm.mongodb.%s_event_subscriber', $manager));
                    }
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
