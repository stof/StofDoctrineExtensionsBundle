<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ConfigFiltersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container->getExtension('stof_doctrine_extensions')->configFilters($container);
    }
}