<?php

namespace Stof\DoctrineExtensionsBundle\ODM\MongoDB;

use Stof\DoctrineExtensionsBundle\AbstractListenerManager;
use Symfony\Component\EventDispatcher\Event;

class ListenerManager extends AbstractListenerManager
{
    public function attachListeners(Event $event)
    {
        $document_managers = $this->container->getParameter('stof_doctrine_extensions.odm.mongodb.document_managers');
        foreach ($document_managers as $name => $listeners) {
            try {
                $dm = $this->container->get(sprintf('doctrine.odm.mongodb.%s_document_manager', $name));
            } catch (\InvalidArgumentException $e){
                throw new \InvalidArgumentException(sprintf('The "%s" document manager does not exist', $name));
            }/*
            if (isset($listeners['tree']) && $listeners['tree']){
                $this->addTreeListener($dm);
            }
            if (isset($listeners['translatable']) && $listeners['translatable']){
                $this->addTranslationListener($dm);
            }
            if (isset($listeners['timestampable']) && $listeners['timestampable']){
                $this->addTimestampableListener($dm);
            }*/
            if (isset($listeners['sluggable']) && $listeners['sluggable']){
                $this->addSluggableListener($dm);
            }
        }
    }

    public function addTimestampableListener($om)
    {
        return;
    }

    public function  addTranslationListener($om)
    {
        return;
    }

    public function addTreeListener($om)
    {
        return;
    }
}
