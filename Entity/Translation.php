<?php

namespace Bundle\DoctrineExtensionsBundle\Entity;

/**
 * Bundle\DoctrineExtensionsBundle\Entity\Translation
 *
 * @orm:Entity(repositoryClass="Gedmo\Translatable\Repository\TranslationRepository")
 * @orm:Table(name="ext_translation", indexes={
 *      @orm:index(name="lookup_idx", columns={"locale", "entity", "foreign_key", "field"})
 * })
 */
class Translation extends AbstractTranslation
{
}
