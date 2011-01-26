<?php

namespace Stof\DoctrineExtensionsBundle\Document;

/**
* Stof\DoctrineExtensionsBundle\Document\AbstractTranslation
*
* @mongodb:MappedSuperclass
*/
abstract class AbstractTranslation
{
    /**
     * @var integer
     *
     * @mongodb:Id
     */
    private $id;

    /**
     * @var string
     *
     * @mongodb:String
     */
    private $locale;

    /**
     * @var string
     *
     * @mongodb:String
     */
    private $objectClass;

    /**
     * @var string
     *
     * @mongodb:String
     */
    private $field;

    /**
     * @var string
     *
     * @mongodb:String(name="foreign_key")
     */
    private $foreignKey;

    /**
     * @var text
     *
     * @mongodb:String
     */
    private $content;
    
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
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text
     */
    public function getContent()
    {
        return $this->content;
    }
}