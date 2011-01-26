<?php

namespace Stof\DoctrineExtensionsBundle\Document;

/**
 * Stof\DoctrineExtensionsBundle\Document\Translation
 *
 * @mongodb:Document(repositoryClass="Gedmo\Translatable\Document\Repository\TranslationRepository")
 * @mongodb:UniqueIndex(name="lookup_unique_idx", keys={
 *         "locale",
 *         "object_class",
 *         "foreign_key",
 *         "field"
 * })
 * @mongodb:Index(name="translations_lookup_idx", keys={
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