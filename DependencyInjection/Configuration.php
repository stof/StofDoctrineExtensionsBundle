<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('stof_doctrine_extensions');

        $rootNode
            ->append($this->getVendorNode('orm'))
            ->append($this->getVendorNode('mongodb'))
            ->append($this->getClassNode())
            ->children()
                ->scalarNode('default_locale')
                    ->cannotBeEmpty()
                    ->defaultValue('en')
                ->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }

    private function getVendorNode($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);

        $node
            ->useAttributeAsKey('id')
            ->prototype('array')
                ->performNoDeepMerging()
                ->children()
                    ->scalarNode('translatable')->defaultTrue()->end()
                    ->scalarNode('timestampable')->defaultTrue()->end()
                    ->scalarNode('sluggable')->defaultTrue()->end()
                    ->scalarNode('tree')->defaultTrue()->end()
                    ->scalarNode('loggable')->defaultTrue()->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    private function getClassNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('class');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('translatable')
                    ->cannotBeEmpty()
                    ->defaultValue('Stof\\DoctrineExtensionsBundle\\Listener\\TranslationListener')
                ->end()
                ->scalarNode('timestampable')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\Timestampable\\TimestampableListener')
                ->end()
                ->scalarNode('sluggable')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\Sluggable\\SluggableListener')
                ->end()
                ->scalarNode('tree')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\Tree\\TreeListener')
                ->end()
                ->scalarNode('loggable')
                    ->cannotBeEmpty()
                    ->defaultValue('Stof\\DoctrineExtensionsBundle\\Listener\\LoggableListener')
                ->end()
            ->end()
        ;

        return $node;
    }
}
