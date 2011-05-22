<?php

namespace Stof\DoctrineExtensionsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping as MongoDB;

/**
 * @MongoDB\MappedSuperclass
 */
abstract class AbstractLogEntry
{
    /**
     * @var integer $id
     *
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var string $action
     *
     * @MongoDB\String
     */
    protected $action;

    /**
     * @var datetime $loggedAt
     *
     * @MongoDB\Index
     * @MongoDB\Date
     */
    protected $loggedAt;

    /**
     * @var string $objectId
     *
     * @MongoDB\String(nullable=true)
     */
    protected $objectId;

    /**
     * @var string $objectClass
     *
     * @MongoDB\Index
     * @MongoDB\String
     */
    protected $objectClass;

    /**
     * @var integer $version
     *
     * @MongoDB\Int
     */
    protected $version;

    /**
     * @var text $data
     *
     * @MongoDB\String(nullable=true)
     */
    protected $data;

    /**
     * @var string $data
     *
     * @MongoDB\Index
     * @MongoDB\String(nullable=true)
     */
    protected $username;

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get object class
     *
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
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
     * Get object id
     *
     * @return string
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set object id
     *
     * @param string $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get loggedAt
     *
     * @return datetime
     */
    public function getLoggedAt()
    {
        return $this->loggedAt;
    }

    /**
     * Set loggedAt
     *
     * @param string $loggedAt
     */
    public function setLoggedAt()
    {
        $this->loggedAt = new \DateTime();
    }

    /**
     * Get data
     *
     * @return array or null
     */
    public function getData()
    {
        return is_string($this->data) ? unserialize($this->data) : null;
    }

    /**
     * Set data
     *
     * @param array $data
     */
    public function setData($data)
    {
        if (is_array($data)) {
            $this->data = serialize($data);
        } else {
            $this->data = $data;
        }
    }

    /**
     * Set current version
     *
     * @param integer $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Get current version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }
}