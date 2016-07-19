<?php

namespace LeanKoala\Integration\MissingRequestBundle\Entity;

use Koalamon\IncidentDashboardBundle\Entity\System;
use Doctrine\ORM\EntityRepository;

class CollectionRepository extends EntityRepository
{
    public function findBySystem(System $system)
    {
        $qb = $this->createQueryBuilder("c")
            ->where(':system MEMBER OF c.systems')
            ->setParameter('system', $system);
        return $qb->getQuery()->getResult();
    }
}
