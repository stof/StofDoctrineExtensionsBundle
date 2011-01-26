<?php

namespace Stof\DoctrineExtensionsBundle\ORM;

use Gedmo\Translatable\TranslationListener as BaseTranslationListener;
use Symfony\Component\HttpFoundation\Session;

/**
 * TranslationListener
 *
 * @author Christophe COEVOET
 */
class TranslationListener extends BaseTranslationListener
{
    protected $_defaultTranslationEntity = 'Stof\DoctrineExtensionsBundle\Entity\Translation';

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
