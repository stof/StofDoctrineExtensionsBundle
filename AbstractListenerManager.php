<?php

namespace Stof\DoctrineExtensionsBundle;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Bundle\FrameworkBundle\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Gedmo\Sluggable\AbstractSluggableListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Tree\TreeListener;
use Gedmo\Translatable\TranslationListener;

abstract class AbstractListenerManager extends ContainerAware
{
    /**
     * @var Gedmo\Timestampable\TimestampableListener
     */
    protected $timestampableListener;

    protected $timestampableAttached = array ();

    /**
     * @var Gedmo\Tree\TreeListener
     */
    protected $treeListener;

    protected $treeAttached = array ();

    /**
     * @var Gedmo\Sluggable\AbstractSluggableListener
     */
    protected $sluggableListener;

    protected $sluggableAttached = array ();

    /**
     * @var Gedmo\Translatable\TranslationListener
     */
    protected $translationListener;

    protected $translationAttached = array ();

    public function  __construct(TimestampableListener $timestampableListener, TreeListener $treeListener, AbstractSluggableListener $sluggableListener, TranslationListener $translationListener, $session = null)
    {
        $this->timestampableListener = $timestampableListener;
        $this->treeListener = $treeListener;
        $this->sluggableListener = $sluggableListener;
        $this->translationListener = $translationListener;
        if (null !== $session) {
            $this->translationListener->setTranslatableLocale($session->getLocale());
        }
    }

    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->connect('core.request', array ($this, 'attachListeners'));
    }

    abstract public function attachListeners(Event $event);

    public function addAllListeners($om)
    {
        $this->addTreeListener($om);
        $this->addTimestampableListener($om);
        $this->addSluggableListener($om);
        $this->addTranslationListener($om);
    }

    public function removeAllListeners($om)
    {
        $this->removeTreeListener($om);
        $this->removeTimestampableListener($om);
        $this->removeSluggableListener($om);
        $this->removeTranslationListener($om);
    }

    public function addTimestampableListener($om)
    {
        $eventManager = $om->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->timestampableAttached) && $this->timestampableAttached[$hash]){
            return;
        }
        $eventManager->addEventSubscriber($this->timestampableListener);
        $this->timestampableAttached[$hash] = true;
    }

    public function removeTimestampableListener($om)
    {
        $eventManager = $om->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->timestampableAttached) && $this->timestampableAttached[$hash]){
            $eventManager->removeEventListener($this->timestampableListener->getSubscribedEvents(), $this->timestampableListener);
            $this->timestampableAttached[$hash] = false;
        }
    }

    public function addSluggableListener($om)
    {
        $eventManager = $om->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->sluggableAttached) && $this->sluggableAttached[$hash]){
            return;
        }
        if (array_key_exists($hash, $this->translationAttached) && $this->translationAttached[$hash]){
            // TranslationListener has to be attached after SluggableListener
            $this->removeTranslationListener($om);
            $eventManager->addEventSubscriber($this->sluggableListener);
            $this->addTranslationListener($om);
        } else {
            $eventManager->addEventSubscriber($this->sluggableListener);
        }
        $this->sluggableAttached[$hash] = true;
    }

    public function removeSluggableListener($om)
    {
        $eventManager = $om->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->sluggableAttached) && $this->sluggableAttached[$hash]){
            $eventManager->removeEventListener($this->sluggableListener->getSubscribedEvents(), $this->sluggableListener);
            $this->sluggableAttached[$hash] = false;
        }
    }

    public function addTreeListener($om)
    {
        $eventManager = $om->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->treeAttached) && $this->treeAttached[$hash]){
            return;
        }
        $eventManager->addEventSubscriber($this->treeListener);
        $this->treeAttached[$hash] = true;
    }

    public function removeTreeListener($om)
    {
        $eventManager = $om->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->treeAttached) && $this->treeAttached[$hash]){
            $eventManager->removeEventListener($this->treeListener->getSubscribedEvents(), $this->treeListener);
            $this->treeAttached[$hash] = false;
        }
    }

    public function addTranslationListener($om)
    {
        $eventManager = $om->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->translationAttached) && $this->translationAttached[$hash]){
            return;
        }
        $eventManager->addEventSubscriber($this->translationListener);
        $this->translationAttached[$hash] = true;
    }

    public function removeTranslationListener($om)
    {
        $eventManager = $om->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->translationAttached) && $this->translationAttached[$hash]){
            $eventManager->removeEventListener($this->translationListener->getSubscribedEvents(), $this->translationListener);
            $this->translationAttached[$hash] = false;
        }
    }
}
