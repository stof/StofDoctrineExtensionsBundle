<?php

namespace Stof\DoctrineExtensionsBundle\Tests\DependencyInjection;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Stof\DoctrineExtensionsBundle\DependencyInjection\StofDoctrineExtensionsExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StofDoctrineExtensionsExtensionTest extends TestCase
{
    /**
     * @return iterable<array{string}>
     */
    public static function provideExtensions()
    {
        return array(
            array('blameable'),
            array('ip_traceable'),
            array('loggable'),
            array('reference_integrity'),
            array('sluggable'),
            array('softdeleteable'),
            array('sortable'),
            array('timestampable'),
            array('translatable'),
            array('tree'),
            array('uploadable'),
        );
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadORMConfig(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = array('orm' => array(
            'default' => array($listener => true),
            'other' => array($listener => true),
        ));

        $extension->load(array($config), $container);

        $this->assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        $this->assertTrue($def->hasTag('doctrine.event_listener'));

        $tags = $def->getTag('doctrine.event_listener');
        $configuredManagers = array_unique(array_column($tags, 'connection'));

        $this->assertCount(2, $configuredManagers);
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadMongodbConfig(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = array('mongodb' => array(
            'default' => array($listener => true),
            'other' => array($listener => true),
        ));

        $extension->load(array($config), $container);

        $this->assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        $this->assertTrue($def->hasTag('doctrine_mongodb.odm.event_listener'));

        $tags = $def->getTag('doctrine_mongodb.odm.event_listener');
        $configuredManagers = array_unique(array_column($tags, 'connection'));

        $this->assertCount(2, $configuredManagers);
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadBothConfig(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = array(
            'orm' => array('default' => array($listener => true)),
            'mongodb' => array('default' => array($listener => true)),
        );

        $extension->load(array($config), $container);

        $this->assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        $this->assertTrue($def->hasTag('doctrine.event_listener'));
        $this->assertTrue($def->hasTag('doctrine_mongodb.odm.event_listener'));

        $this->assertCount(1, array_unique(array_column($def->getTag('doctrine.event_listener'), 'connection')));
        $this->assertCount(1, array_unique(array_column($def->getTag('doctrine_mongodb.odm.event_listener'), 'connection')));
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testEventConsistency(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();
        $container->register('annotation_reader', AnnotationReader::class);

        $config = array('orm' => array(
            'default' => array($listener => true),
        ));

        $extension->load(array($config), $container);

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);
        $configuredEvents = array_column($def->getTag('doctrine.event_listener'), 'event');

        $listenerInstance = $container->get('stof_doctrine_extensions.listener.'.$listener);

        if (!$listenerInstance instanceof EventSubscriber) {
            $this->markTestSkipped(sprintf('The listener for "%s" is not a Doctrine event subscriber.', $listener));
        }

        $this->assertEqualsCanonicalizing($listenerInstance->getSubscribedEvents(), $configuredEvents);
    }
}
