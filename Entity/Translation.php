<?php

namespace Stof\DoctrineExtensionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stof\DoctrineExtensionsBundle\Entity\Translation
 *
 * @ORM\Entity(repositoryClass="Gedmo\Translatable\Entity\Repository\TranslationRepository")
 * @ORM\Table(
 *         name="ext_translations",
 *         indexes={@ORM\index(name="translations_lookup_idx", columns={
 *             "locale", "object_class", "foreign_key"
 *         })},
 *         uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *             "locale", "object_class", "foreign_key", "field"
 *         })}
 * )
 */
class Translation extends AbstractTranslation
{
}
