<?php

namespace Stof\DoctrineExtensionsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping as MongoDB;

/**
 * Stof\DoctrineExtensionsBundle\Document\Translation
 *
 * @MongoDB\Document(repositoryClass="Gedmo\Translatable\Document\Repository\TranslationRepository")
 * @MongoDB\UniqueIndex(name="lookup_unique_idx", keys={
 *         "locale",
 *         "object_class",
 *         "foreign_key",
 *         "field"
 * })
 * @MongoDB\Index(name="translations_lookup_idx", keys={
 *      "locale",
 *      "object_class",
 *      "foreign_key"
 * })
 */
class Translation extends AbstractTranslation
{
    /**
     * All required columns are mapped through inherited superclass
     */
}