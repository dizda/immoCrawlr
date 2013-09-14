<?php

namespace Dizda\UserBundle\Document;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;


    /**
     * @MongoDB\Date
     */
    protected $createdAt;


    public function __construct()
    {
        parent::__construct();
        $this->createdAt = new \DateTime();
    }

}
