<?php

namespace Stof\DoctrineExtensionsBundle\ORM;

use Stof\DoctrineExtensionsBundle\AbstractListenerManager;
use Symfony\Component\EventDispatcher\Event;

class ListenerManager extends AbstractListenerManager
{
    public function attachListeners(Event $event)
    {
        $entity_managers = $this->container->getParameter('stof_doctrine_extensions.orm.entity_managers');
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
}
