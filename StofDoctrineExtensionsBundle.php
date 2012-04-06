<?php

namespace Stof\DoctrineExtensionsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ConfigFiltersPass;

class StofDoctrineExtensionsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
        $container->addCompilerPass(new ConfigFiltersPass());
    }
}
