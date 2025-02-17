<?php

namespace Stof\DoctrineExtensionsBundle\Tests\DependencyInjection;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Stof\DoctrineExtensionsBundle\DependencyInjection\StofDoctrineExtensionsExtension;
use PHPUnit\Framework\TestCase;
use Stof\DoctrineExtensionsBundle\Tests\Mock\FakeUser;
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

        self::assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        self::assertTrue($def->hasTag('doctrine.event_listener'));

        $tags = $def->getTag('doctrine.event_listener');
        $configuredManagers = array_unique(array_column($tags, 'connection'));

        self::assertCount(2, $configuredManagers);
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

        self::assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        self::assertTrue($def->hasTag('doctrine_mongodb.odm.event_listener'));

        $tags = $def->getTag('doctrine_mongodb.odm.event_listener');
        $configuredManagers = array_unique(array_column($tags, 'connection'));

        self::assertCount(2, $configuredManagers);
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

        self::assertTrue($container->hasDefinition('stof_doctrine_extensions.listener.'.$listener));

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);

        self::assertTrue($def->hasTag('doctrine.event_listener'));
        self::assertTrue($def->hasTag('doctrine_mongodb.odm.event_listener'));

        self::assertCount(1, array_unique(array_column($def->getTag('doctrine.event_listener'), 'connection')));
        self::assertCount(1, array_unique(array_column($def->getTag('doctrine_mongodb.odm.event_listener'), 'connection')));
    }

    /**
     * @dataProvider provideExtensions
     */
    public function testEventConsistency(string $listener): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = array('orm' => array(
            'default' => array($listener => true),
        ));

        $extension->load(array($config), $container);

        $def = $container->getDefinition('stof_doctrine_extensions.listener.'.$listener);
        $configuredEvents = array_column($def->getTag('doctrine.event_listener'), 'event');

        $listenerInstance = $container->get('stof_doctrine_extensions.listener.'.$listener);

        if (!$listenerInstance instanceof EventSubscriber) {
            self::markTestSkipped(sprintf('The listener for "%s" is not a Doctrine event subscriber.', $listener));
        }

        self::assertEqualsCanonicalizing($listenerInstance->getSubscribedEvents(), $configuredEvents);
    }

    public function testBlameListenerReset(): void
    {
        $blameableListener = self::createMock(\Gedmo\Blameable\BlameableListener::class);
        $blameableListener
            ->expects(self::exactly(2))
            ->method('setUserValue')
            ->willReturnCallback(function ($user) {
                static $callCount = 0;
                if ($callCount === 0) {
                    self::assertInstanceOf(FakeUser::class, $user);
                } elseif ($callCount === 1) {
                    self::assertNull($user);
                }
                $callCount++;
            });

        $tokenStorage = self::createMock(\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface::class);
        $authorizationChecker = self::createMock(\Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface::class);

        $token = self::createMock(\Symfony\Component\Security\Core\Authentication\Token\TokenInterface::class);
        $token->method('getUser')->willReturn(new FakeUser());

        $tokenStorage->method('getToken')->willReturn($token);
        $authorizationChecker->method('isGranted')->with('IS_AUTHENTICATED_REMEMBERED')->willReturn(true);

        $blameListener = new \Stof\DoctrineExtensionsBundle\EventListener\BlameListener($blameableListener, $tokenStorage, $authorizationChecker);

        $blameListener->onKernelRequest(new \Symfony\Component\HttpKernel\Event\RequestEvent(
            self::createMock(\Symfony\Component\HttpKernel\HttpKernelInterface::class),
            self::createMock(\Symfony\Component\HttpFoundation\Request::class),
            \Symfony\Component\HttpKernel\HttpKernelInterface::MAIN_REQUEST
        ));

        $blameListener->reset();
    }

    public function testBlameListenerHasKernelReset(): void
    {
        $extension = new StofDoctrineExtensionsExtension();
        $container = new ContainerBuilder();

        $config = array('orm' => array(
            'default' => array('blameable' => true),
        ));

        $extension->load(array($config), $container);

        self::assertTrue($container->hasDefinition('stof_doctrine_extensions.event_listener.blame'));

        $def = $container->getDefinition('stof_doctrine_extensions.event_listener.blame');

        self::assertTrue($def->hasTag('kernel.reset'));
    }
}
