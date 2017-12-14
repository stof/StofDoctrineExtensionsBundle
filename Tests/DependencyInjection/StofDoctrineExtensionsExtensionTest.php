<?php

namespace Stof\DoctrineExtensionsBundle\Tests\DependencyInjection;

use Stof\DoctrineExtensionsBundle\DependencyInjection\StofDoctrineExtensionsExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StofDoctrineExtensionsExtensionTest extends TestCase
{
    public static function provideExtensions()
    {
        return array(
            array('blameable'),
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
    public function testLoadORMConfig($listener)
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

        $this->assertTrue($def->hasTag('doctrine.event_subscriber'));

        $tags = $def->getTag('doctrine.event_subscriber');

        $this->assertCount(2, $tags);
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadMongodbConfig($listener)
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

        $this->assertTrue($def->hasTag('doctrine_mongodb.odm.event_subscriber'));

        $tags = $def->getTag('doctrine_mongodb.odm.event_subscriber');

        $this->assertCount(2, $tags);
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadBothConfig($listener)
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

        $this->assertTrue($def->hasTag('doctrine.event_subscriber'));
        $this->assertTrue($def->hasTag('doctrine_mongodb.odm.event_subscriber'));

        $this->assertCount(1, $def->getTag('doctrine.event_subscriber'));
        $this->assertCount(1, $def->getTag('doctrine_mongodb.odm.event_subscriber'));
    }
}
