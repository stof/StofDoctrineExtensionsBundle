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
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());

    }

    public function boot()
    {
        \Gedmo\Uploadable\Mapping\Validator::$validateWritableDirectory = $this->container->getParameter('stof_doctrine_extensions.uploadable.mapping.validator.validate_writable_directory');
    }
}
