<?php

namespace Kingdom\FrontBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartRepository extends EntityRepository
{
    
    public function findForBuying ($ids){
        $qb=$this->createQueryBuilder('c')
        ->where("c.id IN(:ids)")        
        ->setParameter('ids', $ids)
        ->getQuery();
        return $qb->getResult();
        
    }
}
