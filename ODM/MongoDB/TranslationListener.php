<?php

namespace Stof\DoctrineExtensionsBundle\ODM\MongoDB;

use Gedmo\Translatable\ODM\MongoDB\TranslationListener as BaseTranslationListener;
use Symfony\Component\HttpFoundation\Session;

/**
 * TranslationListener
 *
 * @author Christophe COEVOET
 */
class TranslationListener extends BaseTranslationListener
{
    protected $defaultTranslationDocument = 'Stof\DoctrineExtensionsBundle\Document\Translation';

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
