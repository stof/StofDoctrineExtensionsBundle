<?php

namespace Stof\DoctrineExtensionsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping as MongoDB;

/**
* Stof\DoctrineExtensionsBundle\Document\AbstractTranslation
*
* @MongoDB\MappedSuperclass
*/
abstract class AbstractTranslation
{
    /**
     * @var integer
     *
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @MongoDB\String
     */
    protected $locale;

    /**
     * @var string
     *
     * @MongoDB\String
     */
    protected $objectClass;

    /**
     * @var string
     *
     * @MongoDB\String
     */
    protected $field;

    /**
     * @var string
     *
     * @MongoDB\String(name="foreign_key")
     */
    protected $foreignKey;

    /**
     * @var string
     *
     * @MongoDB\String
     */
    protected $content;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set locale
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set field
     *
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set object class
     *
     * @param string $objectClass
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;
    }

    /**
     * Get objectClass
     *
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * Set foreignKey
     *
     * @param string $foreignKey
     */
    public function setForeignKey($foreignKey)
    {
        $this->foreignKey = $foreignKey;
    }

    /**
     * Get foreignKey
     *
     * @return string
     */
    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}