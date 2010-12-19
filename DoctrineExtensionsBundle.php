<?php

namespace Bundle\DoctrineExtensionsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Tree\TreeListener;
use Doctrine\ORM\EntityManager;

class DoctrineExtensionsBundle extends Bundle
{
    /**
     * @var Gedmo\Timestampable\TimestampableListener
     */
    protected static $timestampableListener;

    protected static $timestampableAttached = array();

    /**
     * @var Gedmo\Tree\TreeListener
     */
    protected static $treeListener;

    protected static $treeAttached = array();

    /**
     * @var Gedmo\Sluggable\SluggableListener
     */
    protected static $sluggableListener;

    protected static $sluggableAttached = array();

    /**
     * @var Bundle\DoctrineExtensionsBundle\TranslationListener
     */
    protected static $translationListener;

    protected static $translationAttached = array();

    protected static $default_locale;

    protected static $locale;

    public function boot()
    {
        try {
            $em = $this->container->get($this->container->getParameter('doctrine_extensions.entity_manager'));
        } catch (\InvalidArgumentException $e){
            throw new \InvalidArgumentException('You must provide a Doctrine ORM Entity Manager');
        }

        self::$default_locale = $this->container->getParameter('session.default_locale');
        self::$locale = $this->container->getParameter('session.default_locale');
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
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, self::$timestampableAttached) && self::$timestampableAttached[$hash]){
            return;
        }
        if (null === self::$timestampableListener){
            self::$timestampableListener = new TimestampableListener();
        }
        $eventManager->addEventSubscriber(self::$timestampableListener);
        self::$timestampableAttached[$hash] = true;
    }

    public static function removeTimestampableListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, self::$timestampableAttached) && self::$timestampableAttached[$hash]){
            $eventManager->removeEventListener(self::$timestampableListener->getSubscribedEvents(), self::$timestampableListener);
            self::$timestampableAttached[$hash] = false;
        }
    }

    public static function addSluggableListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, self::$sluggableAttached) && self::$sluggableAttached[$hash]){
            return;
        }
        if (null === self::$sluggableListener){
            self::$sluggableListener = new SluggableListener();
        }
        if (array_key_exists($hash, self::$translationAttached) && self::$translationAttached[$hash]){
            // TranslationListener has to be attached after SluggableListener
            self::removeTranslationListener($em);
            $eventManager->addEventSubscriber(self::$sluggableListener);
            self::addTranslationListener($em);
        } else {
            $eventManager->addEventSubscriber(self::$sluggableListener);
        }
        self::$sluggableAttached[$hash] = true;
    }

    public static function removeSluggableListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, self::$sluggableAttached) && self::$sluggableAttached[$hash]){
            $eventManager->removeEventListener(self::$sluggableListener->getSubscribedEvents(), self::$sluggableListener);
            self::$sluggableAttached[$hash] = false;
        }
    }

    public static function addTreeListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, self::$treeAttached) && self::$treeAttached[$hash]){
            return;
        }
        if (null === self::$treeListener){
            self::$treeListener = new TreeListener();
        }
        $eventManager->addEventSubscriber(self::$treeListener);
        self::$treeAttached[$hash] = true;
    }

    public static function removeTreeListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, self::$treeAttached) && self::$treeAttached[$hash]){
            $eventManager->removeEventListener(self::$treeListener->getSubscribedEvents(), self::$treeListener);
            self::$treeAttached[$hash] = false;
        }
    }

    public static function addTranslationListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, self::$translationAttached) && self::$translationAttached[$hash]){
            return;
        }
        if (null === self::$translationListener){
            self::$translationListener = new TranslationListener();
            self::$translationListener->setDefaultLocale(self::$default_locale);
            self::$translationListener->setTranslatableLocale(self::$locale);
        }
        $eventManager->addEventSubscriber(self::$translationListener);
        self::$translationAttached[$hash] = true;
    }

    public static function removeTranslationListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, self::$translationAttached) && self::$translationAttached[$hash]){
            $eventManager->removeEventListener(self::$translationListener->getSubscribedEvents(), self::$translationListener);
            self::$translationAttached[$hash] = false;
        }
    }

    public static function setTranslatableLocale($locale)
    {
        self::$locale = $locale;
        self::$translationListener->setTranslatableLocale(self::$locale);
    }
}
