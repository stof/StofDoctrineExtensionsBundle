<?php

namespace Bundle\DoctrineExtensionsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use DoctrineExtensions\Sluggable\SluggableListener;
use DoctrineExtensions\Timestampable\TimestampableListener;
use DoctrineExtensions\Tree\TreeListener;
use Doctrine\ORM\EntityManager;

class DoctrineExtensionsBundle extends Bundle
{
    /**
     * @var DoctrineExtensions\Timestampable\TimestampableListener
     */
    protected static $timestampableListener;

    protected static $timestampableAttached = false;

    /**
     * @var DoctrineExtensions\Tree\TreeListener
     */
    protected static $treeListener;

    protected static $treeAttached = false;

    /**
     * @var DoctrineExtensions\Sluggable\SluggableListener
     */
    protected static $sluggableListener;

    protected static $sluggableAttached = false;

    /**
     * @var Bundle\DoctrineExtensionsBundle\TranslationListener
     */
    protected static $translationListener;

    protected static $translationAttached = false;

    protected static $default_locale;

    public function boot()
    {
        try {
            $em = $this->container->get('doctrine.orm.entity_manager');
        } catch (\InvalidArgumentException $e){
            throw new \InvalidArgumentException('You must provide a Doctrine ORM Entity Manager');
        }
        self::$default_locale = $this->container->getParameter('session.default_locale');
        self::addAllListeners($em);
    }

    public static function addAllListeners(EntityManager $em)
    {
        self::addTreeListener($em);
        self::addTimestampableListener($em);
        self::addSluggableListener($em);
        self::addTranslationListener($em);
    }

    public static function removeAllListeners(EntityManager $em)
    {
        self::removeTreeListener($em);
        self::removeTimestampableListener($em);
        self::removeSluggableListener($em);
        self::removeTranslationListener($em);
    }

    public static function addTimestampableListener(EntityManager $em)
    {
        if (self::$timestampableAttached){
            return;
        }
        if (null === self::$timestampableListener){
            self::$timestampableListener = new TimestampableListener();
        }
        $eventManager = $em->getEventManager();
        $eventManager->addEventSubscriber(self::$timestampableListener);
        self::$timestampableAttached = true;
    }

    public static function removeTimestampableListener(EntityManager $em)
    {
        if (self::$timestampableAttached){
            $eventManager = $em->getEventManager();
            $eventManager->removeEventListener(self::$timestampableListener->getSubscribedEvents(), self::$timestampableListener);
            self::$timestampableAttached = false;
        }
    }

    public static function addSluggableListener(EntityManager $em)
    {
        if (self::$sluggableAttached){
            return;
        }
        if (null === self::$sluggableListener){
            self::$sluggableListener = new SluggableListener();
        }
        $eventManager = $em->getEventManager();
        if (self::$translationAttached){
            // TranslationListener has to be attached after SluggableListener
            self::removeTranslationListener($em);
            $eventManager->addEventSubscriber(self::$sluggableListener);
            self::addTranslationListener($em);
        } else {
            $eventManager->addEventSubscriber(self::$sluggableListener);
        }
        self::$sluggableAttached = true;
    }

    public static function removeSluggableListener(EntityManager $em)
    {
        if (self::$sluggableAttached){
            $eventManager = $em->getEventManager();
            $eventManager->removeEventListener(self::$sluggableListener->getSubscribedEvents(), self::$sluggableListener);
            self::$sluggableAttached = false;
        }
    }

    public static function addTreeListener(EntityManager $em)
    {
        if (self::$treeAttached){
            return;
        }
        if (null === self::$treeListener){
            self::$treeListener = new TreeListener();
        }
        $eventManager = $em->getEventManager();
        $eventManager->addEventSubscriber(self::$treeListener);
        self::$treeAttached = true;
    }

    public static function removeTreeListener(EntityManager $em)
    {
        if (self::$treeAttached){
            $eventManager = $em->getEventManager();
            $eventManager->removeEventListener(self::$treeListener->getSubscribedEvents(), self::$treeListener);
            self::$treeAttached = false;
        }
    }

    public static function addTranslationListener(EntityManager $em)
    {
        if (self::$translationAttached){
            return;
        }
        if (null === self::$translationListener){
            self::$translationListener = new TranslationListener();
            self::$translationListener->setTranslatableLocale(self::$default_locale);
        }
        $eventManager = $em->getEventManager();
        $eventManager->addEventSubscriber(self::$translationListener);
        self::$translationAttached = true;
    }

    public static function removeTranslationListener(EntityManager $em)
    {
        if (self::$translationAttached){
            $eventManager = $em->getEventManager();
            $eventManager->removeEventListener(self::$translationListener->getSubscribedEvents(), self::$translationListener);
            self::$translationAttached = false;
        }
    }
}
