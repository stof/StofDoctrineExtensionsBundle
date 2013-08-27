<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('stof_doctrine_extensions');

        $rootNode
            ->append($this->getVendorNode('orm'))
            ->append($this->getVendorNode('mongodb'))
            ->append($this->getClassNode())
            ->append($this->getUploadableNode())
            ->children()
                ->scalarNode('default_locale')
                    ->cannotBeEmpty()
                    ->defaultValue('en')
                ->end()
                ->booleanNode('translation_fallback')->defaultFalse()->end()
                ->booleanNode('persist_default_translation')->defaultFalse()->end()
                ->booleanNode('skip_translation_on_load')->defaultFalse()->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @param string $name
     */
    private function getVendorNode($name)
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root($name);

        $node
            ->useAttributeAsKey('id')
            ->prototype('array')
                ->children()
                    ->scalarNode('translatable')->defaultFalse()->end()
                    ->scalarNode('timestampable')->defaultFalse()->end()
                    ->scalarNode('blameable')->defaultFalse()->end()
                    ->scalarNode('sluggable')->defaultFalse()->end()
                    ->scalarNode('tree')->defaultFalse()->end()
                    ->scalarNode('loggable')->defaultFalse()->end()
                    ->scalarNode('sortable')->defaultFalse()->end()
                    ->scalarNode('softdeleteable')->defaultFalse()->end()
                    ->scalarNode('uploadable')->defaultFalse()->end()
                    ->scalarNode('reference_integrity')->defaultFalse()->end()
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
                    ->defaultValue('Gedmo\Translatable\TranslatableListener')
                ->end()
                ->scalarNode('timestampable')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\Timestampable\\TimestampableListener')
                ->end()
                ->scalarNode('blameable')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\Blameable\\BlameableListener')
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
                    ->defaultValue('Gedmo\Loggable\LoggableListener')
                ->end()
                ->scalarNode('sortable')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\Sortable\\SortableListener')
                ->end()
                ->scalarNode('softdeleteable')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\SoftDeleteable\\SoftDeleteableListener')
                ->end()
                ->scalarNode('uploadable')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\Uploadable\\UploadableListener')
                ->end()
                ->scalarNode('reference_integrity')
                    ->cannotBeEmpty()
                    ->defaultValue('Gedmo\\ReferenceIntegrity\\ReferenceIntegrityListener')
                ->end()
            ->end()
        ;

        return $node;
    }

    private function getUploadableNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('uploadable');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('default_file_path')
                    ->cannotBeEmpty()
                    ->defaultNull()
                ->end()
                ->scalarNode('mime_type_guesser_class')
                    ->cannotBeEmpty()
                    ->defaultValue('Stof\\DoctrineExtensionsBundle\\Uploadable\\MimeTypeGuesserAdapter')
                ->end()
                ->scalarNode('default_file_info_class')
                    ->cannotBeEmpty()
                    ->defaultValue('Stof\\DoctrineExtensionsBundle\\Uploadable\\UploadedFileInfo')
                ->end()
                ->booleanNode('validate_writable_directory')->defaultTrue()->end()
            ->end()
        ;

        return $node;
    }
}
