<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler;

use Gedmo\Mapping\Driver\AttributeReader;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 */
final class ReaderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->has('annotation_reader')) {
            $container->setAlias('.stof_doctrine_extensions.reader', new Alias('annotation_reader', false));

            return;
        }

        $container->register('.stof_doctrine_extensions.reader', AttributeReader::class);
    }
}
