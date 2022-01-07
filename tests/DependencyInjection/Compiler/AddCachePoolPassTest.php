<?php

declare(strict_types=1);

namespace Stof\DoctrineExtensionsBundle\Tests\DependencyInjection\Compiler;

use Gedmo\Mapping\MappedEventSubscriber;
use PHPUnit\Framework\TestCase;
use Stof\DoctrineExtensionsBundle\DependencyInjection\Compiler\AddCachePoolPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class AddCachePoolPassTest extends TestCase
{
    protected function setUp(): void
    {
        if (!method_exists(MappedEventSubscriber::class, 'setCacheItemPool')) {
            $this->markTestSkipped(sprintf('"%s::setCacheItemPool()" does not exist.',
                MappedEventSubscriber::class
            ));
        }
    }

    public function testAddsSetCacheItemPoolCall(): void
    {
        $pass = new AddCachePoolPass();

        $container = new ContainerBuilder();
        $container
            ->setAlias('stof_doctrine_extensions.cache.pool.default', 'cache_service');

        $container
            ->register('test.service.listener')
            ->addTag('stof_doctrine_extensions.listener');

        $pass->process($container);

        $listenerDef = $container->findDefinition('test.service.listener');

        $this->assertTrue($listenerDef->hasMethodCall('setCacheItemPool'));

        [$methodCall, $arguments] = $listenerDef->getMethodCalls()[0];

        $this->assertSame('setCacheItemPool', $methodCall);
        $this->assertSame('stof_doctrine_extensions.cache.pool.default', (string) $arguments[0]);
    }
}
