<?php

namespace Bundle\DoctrineExtensionsBundle\Entity;

/**
 * Bundle\DoctrineExtensionsBundle\Entity\AbstractTranslationEntity
 *
 * @orm:Table(indexes={
 *      @orm:index(name="lookup_idx", columns={"locale", "entity", "foreign_key", "field"})
 * })
 * @orm:MappedSuperclass(repositoryClass="DoctrineExtensions\Translatable\Repository\TranslationRepository")
 */
abstract class AbstractTranslationEntity
{
    /**
     * @var integer $id
     *
     * @orm:Column(name="id", type="integer")
     * @orm:Id
     * @orm:GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $locale
     *
     * @orm:Column(name="locale", type="string", length=8)
     */
    private $locale;

    /**
     * @var string $entity
     *
     * @orm:Column(name="entity", type="string", length=255)
     */
    private $entity;

    /**
     * @var string $field
     *
     * @orm:Column(name="field", type="string", length=32)
     */
    private $field;

    /**
     * @var string $foreignKey
     *
     * @orm:Column(name="foreign_key", type="string", length="64")
     */
    private $foreignKey;

    /**
     * @var text $content
     *
     * @orm:Column(name="content", type="text", nullable=true)
     */
    private $content;
    
    /**
     * Get id
     *
     * @return integer $id
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
     * @return string $locale
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
     * @return string $field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set entity
     *
     * @param string $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     *
     * @return string $entity
     */
    public function getEntity()
    {
        return $this->entity;
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
     * @return string $foreignKey
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
     * @return text $content
     */
    public function getContent()
    {
        return $this->content;
    }
}