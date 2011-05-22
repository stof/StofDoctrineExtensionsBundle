<?php

namespace Stof\DoctrineExtensionsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Gedmo\Loggable\Document\Repository\LogEntryRepository")
 */
class LogEntry extends AbstractLogEntry
{
    /**
     * All required columns are mapped through inherited superclass
     */
}