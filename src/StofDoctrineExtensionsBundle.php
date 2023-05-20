<?php

namespace Stof\DoctrineExtensionsBundle;

use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StofDoctrineExtensionsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
    }
}
