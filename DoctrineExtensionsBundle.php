<?php

namespace Bundle\DoctrineExtensionsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use DoctrineExtensions\Sluggable\SluggableListener;
use DoctrineExtensions\Timestampable\TimestampableListener;
use DoctrineExtensions\Tree\TreeListener;

class DoctrineExtensionsBundle extends Bundle
{
    public function boot()
    {
        try {
            $em = $this->container->get('doctrine.orm.entity_manager');
        } catch (\InvalidArgumentException $e){
            throw new \InvalidArgumentException('You must provide a Doctrine ORM Entity Manager');
        }
        $eventManager = $em->getEventManager();
        $treeListener = new TreeListener();
        $eventManager->addEventSubscriber($treeListener);
        $timestampableListener = new TimestampableListener();
        $eventManager->addEventSubscriber($timestampableListener);
        $sluggableListener = new SluggableListener();
        $eventManager->addEventSubscriber($sluggableListener);
        $translatableListener = new TranslationListener();
        $translatableListener->setTranslatableLocale($this->container->get('doctrine_extensions.default_locale'));
        $eventManager->addEventSubscriber($translatableListener);
    }
}
