<?php

namespace Stof\DoctrineExtensionsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;

class StofDoctrineExtensionsBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function registerExtensions(ContainerBuilder $container)
    {
        parent::registerExtensions($container);
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
