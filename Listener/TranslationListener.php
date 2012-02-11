<?php

namespace Stof\DoctrineExtensionsBundle\Listener;

use Gedmo\Translatable\TranslatableListener as BaseTranslationListener;
use Gedmo\Translatable\Mapping\Event\TranslatableAdapter;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * TranslationListener
 *
 * @author Christophe COEVOET
 */
class TranslationListener extends BaseTranslationListener
{
    public function getTranslationClass(TranslatableAdapter $ea, $class)
    {
        $class = parent::getTranslationClass($ea, $class);

        if ($class === 'Gedmo\\Translatable\\Entity\\Translation') {
            return 'Stof\\DoctrineExtensionsBundle\\Entity\\Translation';
        } elseif ($class === 'Gedmo\\Translatable\\Document\\Translation') {
            return 'Stof\\DoctrineExtensionsBundle\\Document\\Translation';
        }

        return $class;
    }

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
