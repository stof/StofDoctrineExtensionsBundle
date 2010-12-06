<?php

namespace Bundle\DoctrineExtensionsBundle\Listener;

use Symfony\Bundle\FrameworkBundle\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Bundle\DoctrineExtensionsBundle\DoctrineExtensionsBundle;

/**
 * DoctrineExtensionsListener
 */
class DoctrineExtensionsListener
{
    public function register(EventDispatcher $dispatcher)
    {
        $dispatcher->connect('core.request', array ($this, 'setTranslatableLocale'));
    }

    public function setTranslatableLocale(Event $event)
    {
        DoctrineExtensionsBundle::setTranslatableLocale($event->get('request')->getSession()->getLocale());
        return false;
    }
}
