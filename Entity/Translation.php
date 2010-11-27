<?php

namespace Bundle\DoctrineExtensionsBundle\Entity;

/**
 * Bundle\DoctrineExtensionsBundle\Entity\Translation
 *
 * @orm:Entity(repositoryClass="Gedmo\Translatable\Repository\TranslationRepository")
 * @orm:Table(
 *         name="ext_translations",
 *         indexes={@orm:index(name="translations_lookup_idx", columns={
 *             "locale", "entity", "foreign_key"
 *         })},
 *         uniqueConstraints={@orm:UniqueConstraint(name="lookup_unique_idx", columns={
 *             "locale", "entity", "foreign_key", "field"
 *         })}
 * )
 */
class Translation extends AbstractTranslation
{
}
