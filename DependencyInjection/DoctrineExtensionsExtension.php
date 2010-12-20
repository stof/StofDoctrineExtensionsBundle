<?php

namespace Bundle\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineExtensionsExtension extends Extension
{
    public function configLoad(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
        $loader->load('listener_manager.xml');

        $defaultListeners = array (
            'tree' => true,
            'timestampable' => true,
            'translatable' => true,
            'sluggable' => true,
        );

        $entity_managers = array ();
        $emConfig = $config;
        if (isset($config['entity-managers'])){
            $emConfig = $config['entity-managers'];
        }
        foreach ($emConfig as $name => $listeners){
            if (null === $listeners){
                $listeners = array ();
            }
            $entity_managers[isset($listeners['id']) ? $listeners['id'] : $name] = array_merge($defaultListeners, $listeners);
        }
        $container->setParameter('doctrine_extensions.entity_managers', $entity_managers);
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
        return 'http://www.symfony-project.org/schema/dic/doctrine_extensions';
    }

    public function getAlias()
    {
        return 'doctrine_extensions';
    }
}
