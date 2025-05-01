<?php

namespace Stof\DoctrineExtensionsBundle\Tool;

use Gedmo\Translatable\TranslatableListener;
use Symfony\Contracts\Translation\LocaleAwareInterface;

/**
 * @internal
 */
final class LocaleSynchronizer implements LocaleAwareInterface
{
    private TranslatableListener $listener;

    public function __construct(TranslatableListener $listener)
    {
        $this->listener = $listener;
    }

    public function setLocale(string $locale): void
    {
        $this->listener->setTranslatableLocale($locale);
    }

    public function getLocale(): string
    {
        return $this->listener->getListenerLocale();
    }
}
