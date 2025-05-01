<?php

namespace Stof\DoctrineExtensionsBundle\EventListener;

use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This listener sets the current locale for the TranslatableListener
 *
 * @author Christophe COEVOET
 *
 * @deprecated since 1.14. Use the LocaleSynchronizer instead.
 */
class LocaleListener implements EventSubscriberInterface
{
    private TranslatableListener $translatableListener;

    public function __construct(TranslatableListener $translatableListener)
    {
        $this->translatableListener = $translatableListener;
    }

    /**
     * @internal
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $this->translatableListener->setTranslatableLocale($event->getRequest()->getLocale());
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}
