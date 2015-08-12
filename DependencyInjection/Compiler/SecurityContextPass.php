<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SecurityContextPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('security.token_storage')) {
            $args = array(new Reference('security.token_storage'), new Reference('security.authorization_checker'));
        } elseif ($container->has('security.context')) {
            $args = array(new Reference('security.context'));
        } else {
            return; // SecurityBundle is not configured
        }

        $defs = array(
            $container->findDefinition('stof_doctrine_extensions.event_listener.blame'),
            $container->findDefinition('stof_doctrine_extensions.event_listener.logger'),
        );

        foreach ($defs as $def) {
            foreach ($args as $argument) {
                $def->addArgument($argument);
            }
        }
    }
}
