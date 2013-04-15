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
            ->field('discriminatorType')->equals($discriminator)
            ->getQuery()
            ->execute()['count'];
    }

}