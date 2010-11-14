<?php

namespace Bundle\DoctrineExtensionsBundle;

use Gedmo\Translatable\TranslationListener as BaseTranslationListener;

/**
 * TranslationListener
 *
 * @author Christophe COEVOET
 */
class TranslationListener extends BaseTranslationListener
{
    const TRANSLATION_ENTITY_CLASS = 'Bundle\DoctrineExtensionsBundle\Entity\Translation';
}
