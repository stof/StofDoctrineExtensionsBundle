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
            ->children()
                ->scalarNode('default_locale')
                    ->cannotBeEmpty()
                    ->defaultValue('en')
                ->end()
                ->arrayNode('class')
                    ->append($this->getVendorClassNode('orm'))
                    ->append($this->getVendorClassNode('mongodb'))
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

    private function getVendorClassNode($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);

        $node
            ->children()
                ->scalarNode('translatable')->end()
                ->scalarNode('timestampable')->end()
                ->scalarNode('sluggable')->end()
                ->scalarNode('tree')->end()
                ->scalarNode('loggable')->end()
            ->end()
        ;

        return $node;
    }
}
