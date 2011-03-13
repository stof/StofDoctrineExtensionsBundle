<?php

namespace Stof\DoctrineExtensionsBundle\Entity;

/**
 * @orm:Table(
 *     name="ext_log_entries",
 *  indexes={
 *      @orm:index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @orm:index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @orm:index(name="log_user_lookup_idx", columns={"username"})
 *  }
 * )
 * @orm:Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 */
class LogEntry extends AbstractLogEntry
{
    /**
     * All required columns are mapped through inherited superclass
     */
}