<?php

namespace Bundle\DoctrineExtensionsBundle\Entity;

/**
 * Bundle\DoctrineExtensionsBundle\Entity\TranslationEntity
 *
 * @orm:Entity(repositoryClass="DoctrineExtensions\Translatable\Repository\TranslationRepository")
 * @orm:Table(name="doctrine_extensions_translation", indexes={
 *      @orm:index(name="lookup_idx", columns={"locale", "entity", "foreign_key", "field"})
 * })
 */
class TranslationEntity extends AbstractTranslation
{
}
