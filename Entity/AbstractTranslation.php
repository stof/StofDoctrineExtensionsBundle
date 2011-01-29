<?php

namespace Stof\DoctrineExtensionsBundle\Entity;

/**
 * Stof\DoctrineExtensionsBundle\Entity\AbstractTranslation
 *
 * @orm:MappedSuperclass
 */
abstract class AbstractTranslation
{
    /**
     * @var integer
     *
     * @orm:Column(name="id", type="integer")
     * @orm:Id
     * @orm:GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @orm:Column(name="locale", type="string", length=8)
     */
    private $locale;

    /**
     * @var string
     *
     * @orm:Column(name="object_class", type="string", length=255)
     */
    private $objectClass;

    /**
     * @var string
     *
     * @orm:Column(name="field", type="string", length=32)
     */
    private $field;

    /**
     * @var string
     *
     * @orm:Column(name="foreign_key", type="string", length="64")
     */
    private $foreignKey;

    /**
     * @var string
     *
     * @orm:Column(name="content", type="text", nullable=true)
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