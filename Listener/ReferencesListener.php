<?php

namespace Stof\DoctrineExtensionsBundle\Listener;

use Gedmo\References\ReferencesListener as BaseReferencesListener;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ReferencesListener
 *
 * @author Bulat Shakirzyanov <mallluhuct@gmail.com>
 */
class ReferencesListener extends BaseReferencesListener
{
    private $container;

    public function __construct(ContainerInterface $container, array $managers = array())
    {
        $this->container = $container;
        parent::__construct($managers);
    }

    public function getManager($type)
    {
        return $this->container->get(parent::getManager($type));
    }
}
