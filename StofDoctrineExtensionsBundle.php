<?php

namespace Stof\DoctrineExtensionsBundle;

use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\SecurityContextPass;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Gedmo\Uploadable\Mapping\Validator;

class StofDoctrineExtensionsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
        $container->addCompilerPass(new SecurityContextPass());
    }

    public function boot()
    {
        if ($this->container->hasParameter('stof_doctrine_extensions.uploadable.validate_writable_directory')) {
            Validator::$validateWritableDirectory = $this->container->getParameter('stof_doctrine_extensions.uploadable.validate_writable_directory');
        }
    }
}
