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

        $this->addVendorConfig($rootNode, 'orm');
        $this->addVendorConfig($rootNode, 'mongodb');

        $classNode = $rootNode->children()->arrayNode('class');
        $this->addVendorClassConfig($classNode, 'orm');
        $this->addVendorClassConfig($classNode, 'mongodb');

        return $treeBuilder->buildTree();
    }

    private function addVendorConfig(ArrayNodeDefinition $node, $name)
    {
        $node
            ->children()
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
                ->end()
            ->end();
    }

    private function addVendorClassConfig(ArrayNodeDefinition $node, $name)
    {
        $node
            ->children()
                ->arrayNode($name)
                    ->children()
                        ->scalarNode('translatable')->end()
                        ->scalarNode('timestampable')->end()
                        ->scalarNode('sluggable')->end()
                        ->scalarNode('tree')->end()
                        ->scalarNode('loggable')->end()
                    ->end()
                ->end()
            ->end();
    }
}
