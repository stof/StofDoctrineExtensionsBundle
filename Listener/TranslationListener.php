<?php

namespace Stof\DoctrineExtensionsBundle\Listener;

use Gedmo\Translatable\TranslationListener as BaseTranslationListener;
use Gedmo\Translatable\Mapping\Event\TranslatableAdapter;
use Symfony\Component\HttpFoundation\Session;

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
     * Set the translation listener locale from the session.
     *
     * @param Session $session
     * @return void
     */
    public function setTranslatableLocaleFromSession(Session $session = null)
    {
        if ($session !== null) {
            $this->setTranslatableLocale($session->getLocale());
        }
    }
}
