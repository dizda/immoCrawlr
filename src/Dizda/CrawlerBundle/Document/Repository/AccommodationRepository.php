<?php

namespace Dizda\CrawlerBundle\Document\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;



class AccommodationRepository extends DocumentRepository
{

    public function countDiscriminator($discriminator)
    {
        return $this->createQueryBuilder('CrawlerBundle:Accommodation')
            ->group(array(), array('count' => 0))
            ->reduce('function (obj, prev) { prev.count++; }')
            ->field('parent')->equals(true)
            ->field('discriminatorType')->equals($discriminator)
            ->getQuery()
            ->execute()['count'];
    }

    /**
     * Find accommodations whose not putted in trash by current user
     *
     * @param User $user
     *
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|int|mixed|\MongoCursor|null
     */
    public function findUntrashed($user)
    {
        $qb = $this->createQueryBuilder('CrawlerBundle:Accommodation')
            ->field('parent')->equals(true)
            ->field('hidden.$id')->notEqual(new \MongoId($user->getId()))
            ->sort('remoteUpdatedAt', 'desc')
            ->sort('localUpdatedAt', 'desc');

        return $qb->getQuery()->execute();
    }

}