<?php

namespace Stof\DoctrineExtensionsBundle\Tests\DependencyInjection;

use Stof\DoctrineExtensionsBundle\DependencyInjection\StofDoctrineExtensionsExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StofDoctrineExtensionsExtensionTest extends TestCase
{
    public static function provideExtensions(): array
    {
        return [
            ['blameable'],
            ['loggable'],
            ['reference_integrity'],
            ['sluggable'],
            ['softdeleteable'],
            ['sortable'],
            ['timestampable'],
            ['translatable'],
            ['tree'],
            ['uploadable'],
        ];
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadORMConfig($listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = [
            'orm' => [
            'default' => [$listener => true],
            'other' => [$listener => true],
            ]
        ];

        $extension->load([$config], $container);

        $this->assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        $this->assertTrue($def->hasTag('doctrine.event_subscriber'));

        $tags = $def->getTag('doctrine.event_subscriber');

        $this->assertCount(2, $tags);
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadMongodbConfig($listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = [
            'mongodb' => [
            'default' => [$listener => true],
            'other' => [$listener => true],
            ]
        ];

        $extension->load([$config], $container);

        $this->assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        $this->assertTrue($def->hasTag('doctrine_mongodb.odm.event_subscriber'));

        $tags = $def->getTag('doctrine_mongodb.odm.event_subscriber');

        $this->assertCount(2, $tags);
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testLoadBothConfig($listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = [
            'orm' => ['default' => [$listener => true]],
            'mongodb' => ['default' => [$listener => true]],
        ];

        $extension->load([$config], $container);

        $this->assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        $this->assertTrue($def->hasTag('doctrine.event_subscriber'));
        $this->assertTrue($def->hasTag('doctrine_mongodb.odm.event_subscriber'));

        $this->assertCount(1, $def->getTag('doctrine.event_subscriber'));
        $this->assertCount(1, $def->getTag('doctrine_mongodb.odm.event_subscriber'));
    }
}
