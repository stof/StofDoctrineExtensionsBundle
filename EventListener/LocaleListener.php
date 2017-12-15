<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This listeners sets the current locale for the TranslatableListener
 *
 * @author Christophe COEVOET
 */
class LocaleListener implements EventSubscriberInterface
{
    private $translatableListener;

    public function __construct(TranslatableListener $translatableListener)
    {
        $this->translatableListener = $translatableListener;
    }

    /**
     * @internal
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->translatableListener->setTranslatableLocale($event->getRequest()->getLocale());
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}
