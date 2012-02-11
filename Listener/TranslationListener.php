<?php

namespace Stof\DoctrineExtensionsBundle\Listener;

use Gedmo\Translatable\TranslatableListener as BaseTranslationListener;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * TranslationListener
 *
 * @author Christophe COEVOET
 */
class TranslationListener extends BaseTranslationListener
{
    /**
     * Set the translation listener locale from the request.
     *
     * This method should be attached to the kernel.request event.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->setTranslatableLocale($event->getRequest()->getLocale());
    }
}
