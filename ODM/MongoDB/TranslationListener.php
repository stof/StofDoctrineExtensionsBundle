<?php

namespace Stof\DoctrineExtensionsBundle\ODM\MongoDB;

use Gedmo\Translatable\ODM\MongoDB\TranslationListener as BaseTranslationListener;

/**
 * TranslationListener
 *
 * @author Christophe COEVOET
 */
class TranslationListener extends BaseTranslationListener
{
    protected $defaultTranslationDocument = 'Stof\DoctrineExtensionsBundle\Document\Translation';
}
