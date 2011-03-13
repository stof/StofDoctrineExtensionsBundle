<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

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
        $rootNode = $treeBuilder->root('stof_doctrine_extensions', 'array');

        $this->addVendorConfig($rootNode, 'orm');
        $this->addVendorConfig($rootNode, 'mongodb');

        $classNode = $rootNode->arrayNode('class');
        $this->addVendorClassConfig($classNode, 'orm');
        $this->addVendorClassConfig($classNode, 'mongodb');

        return $treeBuilder->buildTree();
    }

    private function addVendorConfig(NodeBuilder $node, $name)
    {
        $node
            ->arrayNode($name)
                ->useAttributeAsKey('id')
                ->prototype('array')
                    ->performNoDeepMerging()
                    ->scalarNode('translatable')->defaultTrue()->end()
                    ->scalarNode('timestampable')->defaultTrue()->end()
                    ->scalarNode('sluggable')->defaultTrue()->end()
                    ->scalarNode('tree')->defaultTrue()->end()
                    ->scalarNode('loggable')->defaultTrue()->end()
                ->end()
            ->end();
    }

    private function addVendorClassConfig(NodeBuilder $node, $name)
    {
        $node
            ->arrayNode($name)
                ->scalarNode('translatable')->end()
                ->scalarNode('timestampable')->end()
                ->scalarNode('sluggable')->end()
                ->scalarNode('tree')->end()
                ->scalarNode('loggable')->end()
            ->end();
    }
}
