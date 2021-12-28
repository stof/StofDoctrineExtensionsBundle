<?php

namespace Stof\DoctrineExtensionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Stof\DoctrineExtensionsBundle\Uploadable\UploadedFileInfo;
use Stof\DoctrineExtensionsBundle\Uploadable\MimeTypeGuesserAdapter;
use Gedmo\ReferenceIntegrity\ReferenceIntegrityListener;
use Gedmo\Uploadable\UploadableListener;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Gedmo\Sortable\SortableListener;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Tree\TreeListener;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\Blameable\BlameableListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Translatable\TranslatableListener;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('stof_doctrine_extensions');
        $rootNode = $treeBuilder->getRootNode();

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

    private function getVendorNode(string $name): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder($name);
        $node = $treeBuilder->getRootNode();

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

    private function getClassNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('class');
        $node = $treeBuilder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('translatable')
                    ->cannotBeEmpty()
                    ->defaultValue(TranslatableListener::class)
                ->end()
                ->scalarNode('timestampable')
                    ->cannotBeEmpty()
                    ->defaultValue(TimestampableListener::class)
                ->end()
                ->scalarNode('blameable')
                    ->cannotBeEmpty()
                    ->defaultValue(BlameableListener::class)
                ->end()
                ->scalarNode('sluggable')
                    ->cannotBeEmpty()
                    ->defaultValue(SluggableListener::class)
                ->end()
                ->scalarNode('tree')
                    ->cannotBeEmpty()
                    ->defaultValue(TreeListener::class)
                ->end()
                ->scalarNode('loggable')
                    ->cannotBeEmpty()
                    ->defaultValue(LoggableListener::class)
                ->end()
                ->scalarNode('sortable')
                    ->cannotBeEmpty()
                    ->defaultValue(SortableListener::class)
                ->end()
                ->scalarNode('softdeleteable')
                    ->cannotBeEmpty()
                    ->defaultValue(SoftDeleteableListener::class)
                ->end()
                ->scalarNode('uploadable')
                    ->cannotBeEmpty()
                    ->defaultValue(UploadableListener::class)
                ->end()
                ->scalarNode('reference_integrity')
                    ->cannotBeEmpty()
                    ->defaultValue(ReferenceIntegrityListener::class)
                ->end()
            ->end()
        ;

        return $node;
    }

    private function getUploadableNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('uploadable');
        $node = $treeBuilder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('default_file_path')
                    ->cannotBeEmpty()
                    ->defaultNull()
                ->end()
                ->scalarNode('mime_type_guesser_class')
                    ->cannotBeEmpty()
                    ->defaultValue(MimeTypeGuesserAdapter::class)
                ->end()
                ->scalarNode('default_file_info_class')
                    ->cannotBeEmpty()
                    ->defaultValue(UploadedFileInfo::class)
                ->end()
                ->booleanNode('validate_writable_directory')->defaultTrue()->end()
            ->end()
        ;

        return $node;
    }
}
