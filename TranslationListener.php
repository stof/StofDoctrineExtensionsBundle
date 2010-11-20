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
    protected $_defaultTranslationEntity = 'Bundle\DoctrineExtensionsBundle\Entity\Translation';
}
