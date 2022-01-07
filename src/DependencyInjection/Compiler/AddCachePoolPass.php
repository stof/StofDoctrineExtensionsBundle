<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler;

use Gedmo\Mapping\MappedEventSubscriber;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal
 */
final class AddCachePoolPass implements CompilerPassInterface
{
    /**
     * Sets a cache item pool to the gedmo/doctrine-extension listeners when
     * it is possible.
     */
    public function process(ContainerBuilder $container): void
    {
        if (!method_exists(MappedEventSubscriber::class, 'setCacheItemPool')) {
            return;
        }

        if (!$container->hasAlias('stof_doctrine_extensions.cache.pool.default')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('stof_doctrine_extensions.listener') as $id => $tags) {
            $listenerDef = $container->getDefinition($id);

            if ($listenerDef->hasMethodCall('setCacheItemPool')) {
                continue;
            }

            $listenerDef->addMethodCall('setCacheItemPool', [new Reference('stof_doctrine_extensions.cache.pool.default')]);
        }
    }
}
