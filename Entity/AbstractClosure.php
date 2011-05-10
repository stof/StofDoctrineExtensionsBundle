<?php

namespace Stof\DoctrineExtensionsBundle\Entity;

/**
 * @orm:MappedSuperclass
 */
abstract class AbstractClosure
{
    /**
     * @orm:Id
     * @orm:GeneratedValue
     * @orm:Column(type="integer")
     */
    private $id;

    /**
     * Mapped by listener
     * Visibility must be protected
     */
    protected $ancestor;

    /**
     * Mapped by listener
     * Visibility must be protected
     */
    protected $descendant;

    /**
     * @orm:Column(type="integer")
     */
    private $depth;

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
     * Set ancestor
     *
     * @param object $ancestor
     * @return AbstractClosure
     */
    public function setAncestor($ancestor)
    {
        $this->ancestor = $ancestor;
        return $this;
    }

    /**
     * Get ancestor
     *
     * @return object
     */
    public function getAncestor()
    {
        return $this->ancestor;
    }

    /**
     * Set descendant
     *
     * @param object $descendant
     * @return AbstractClosure
     */
    public function setDescendant($descendant)
    {
        $this->descendant = $descendant;
        return $this;
    }

    /**
     * Get descendant
     *
     * @return object
     */
    public function getDescendant()
    {
        return $this->descendant;
    }

    /**
     * Set depth
     *
     * @param integer $depth
     * @return AbstractClosure
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
        return $this;
    }

    /**
     * Get depth
     *
     * @return integer
     */
    public function getDepth()
    {
        return $this->depth;
    }
}
