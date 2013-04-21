<?php
namespace Dizda\SiteBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;


/**
 * @MongoDB\EmbeddedDocument */
class Note
{
    /** @MongoDB\Id(strategy="auto") */
    private $id;

    /** La balance au moment T
     *  @MongoDB\String */
    private $text;

    /** @MongoDB\Date */
    private $createdAt;

    /** @MongoDB\Date */
    private $updatedAt;

    /**
     *  @MongoDB\ReferenceOne(targetDocument="Dizda\UserBundle\Document\User")
     */
    private $user;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /** @MongoDB\PreUpdate */
    public function preUpdated()
    {
        $this->updatedAt = new \DateTime();
    }


    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return \Note
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param date $createdAt
     * @return \Note
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return date $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param date $updatedAt
     * @return \Note
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return date $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set user
     *
     * @param Dizda\UserBundle\Document\User $user
     * @return \Note
     */
    public function setUser(\Dizda\UserBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return Dizda\UserBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }
}
