<?php

namespace Stof\DoctrineExtensionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="ext_log_entries",
 *  indexes={
 *      @ORM\index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @ORM\index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @ORM\index(name="log_user_lookup_idx", columns={"username"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 */
class LogEntry extends AbstractLogEntry
{
    /**
     * All required columns are mapped through inherited superclass
     */
}