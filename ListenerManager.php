<?php

namespace Bundle\DoctrineExtensionsBundle;

use Symfony\Component\DependencyInjection\ContainerAware;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Tree\TreeListener;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

class ListenerManager extends ContainerAware
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
     * @var Gedmo\Sluggable\SluggableListener
     */
    protected $sluggableListener;

    protected $sluggableAttached = array ();

    /**
     * @var Bundle\DoctrineExtensionsBundle\TranslationListener
     */
    protected $translationListener;

    protected $translationAttached = array ();

    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->connect('core.request', array ($this, 'attachListeners'));
    }

    public function attachListeners(Event $event)
    {
        $entity_managers = $this->container->getParameter('doctrine_extensions.entity_managers');
        foreach ($entity_managers as $name => $listeners) {
            try {
                $em = $this->container->get(sprintf('doctrine.orm.%s_entity_manager', $name));
            } catch (\InvalidArgumentException $e){
                throw new \InvalidArgumentException(sprintf('The "%s" entity manager does not exist', $name));
            }
            if (isset($listeners['tree']) && $listeners['tree']){
                $this->addTreeListener($em);
            }
            if (isset($listeners['translatable']) && $listeners['translatable']){
                $this->addTranslationListener($em);
            }
            if (isset($listeners['timestampable']) && $listeners['timestampable']){
                $this->addTimestampableListener($em);
            }
            if (isset($listeners['sluggable']) && $listeners['sluggable']){
                $this->addSluggableListener($em);
            }
        }
    }

    public function addAllListeners(EntityManager $em)
    {
        $this->addTreeListener($em);
        $this->addTimestampableListener($em);
        $this->addSluggableListener($em);
        $this->addTranslationListener($em);
    }

    public function removeAllListeners(EntityManager $em)
    {
        $this->removeTreeListener($em);
        $this->removeTimestampableListener($em);
        $this->removeSluggableListener($em);
        $this->removeTranslationListener($em);
    }

    public function addTimestampableListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->timestampableAttached) && $this->timestampableAttached[$hash]){
            return;
        }
        if (null === $this->timestampableListener){
            $this->timestampableListener = new TimestampableListener();
        }
        $eventManager->addEventSubscriber($this->timestampableListener);
        $this->timestampableAttached[$hash] = true;
    }

    public function removeTimestampableListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->timestampableAttached) && $this->timestampableAttached[$hash]){
            $eventManager->removeEventListener($this->timestampableListener->getSubscribedEvents(), $this->timestampableListener);
            $this->timestampableAttached[$hash] = false;
        }
    }

    public function addSluggableListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->sluggableAttached) && $this->sluggableAttached[$hash]){
            return;
        }
        if (null === $this->sluggableListener){
            $this->sluggableListener = new SluggableListener();
        }
        if (array_key_exists($hash, $this->translationAttached) && $this->translationAttached[$hash]){
            // TranslationListener has to be attached after SluggableListener
            $this->removeTranslationListener($em);
            $eventManager->addEventSubscriber($this->sluggableListener);
            $this->addTranslationListener($em);
        } else {
            $eventManager->addEventSubscriber($this->sluggableListener);
        }
        $this->sluggableAttached[$hash] = true;
    }

    public function removeSluggableListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->sluggableAttached) && $this->sluggableAttached[$hash]){
            $eventManager->removeEventListener($this->sluggableListener->getSubscribedEvents(), $this->sluggableListener);
            $this->sluggableAttached[$hash] = false;
        }
    }

    public function addTreeListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->treeAttached) && $this->treeAttached[$hash]){
            return;
        }
        if (null === $this->treeListener){
            $this->treeListener = new TreeListener();
        }
        $eventManager->addEventSubscriber($this->treeListener);
        $this->treeAttached[$hash] = true;
    }

    public function removeTreeListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->treeAttached) && $this->treeAttached[$hash]){
            $eventManager->removeEventListener($this->treeListener->getSubscribedEvents(), $this->treeListener);
            $this->treeAttached[$hash] = false;
        }
    }

    public function addTranslationListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->translationAttached) && $this->translationAttached[$hash]){
            return;
        }
        if (null === $this->translationListener){
            $this->translationListener = new TranslationListener();
            $this->translationListener->setDefaultLocale($this->container->getParameter('session.default_locale'));
            $this->translationListener->setTranslatableLocale($this->container->get('session')->getLocale());
        }
        $eventManager->addEventSubscriber($this->translationListener);
        $this->translationAttached[$hash] = true;
    }

    public function removeTranslationListener(EntityManager $em)
    {
        $eventManager = $em->getEventManager();
        $hash = spl_object_hash($eventManager);
        if (array_key_exists($hash, $this->translationAttached) && $this->translationAttached[$hash]){
            $eventManager->removeEventListener($this->translationListener->getSubscribedEvents(), $this->translationListener);
            $this->translationAttached[$hash] = false;
        }
    }
}
