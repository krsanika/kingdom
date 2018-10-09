<?php

namespace Kingdom\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QnaRepository extends EntityRepository
{
    public function findForAdmin($userId, $start, $limit){

        if($userId != 0){
            $qb = $this->createQueryBuilder("q")
                ->leftJoin("q.files", "f")                
                ->where("q.userId = :userId")
                ->orderBy("q.updated", "DESC")                
                ->setParameter("userId", $userId)
                ->getQuery();                        
        }else{
            $qb = $this->createQueryBuilder("q")
                ->leftJoin("q.files", "f")                
                ->orderBy("q.updated", "DESC")                
                ->setFirstResult($start)
                ->setMaxResults($limit)
                ->getQuery();            
        }
        
        try{            
            $result = $qb->getResult();
        }catch (\NoResultException $e){
            return null;
        }
                
        
        return $result;
    }
    
        
    public function maxPage($max){
        $qnas = $this->findAll();
        return floor(count($qnas) / $max);
    }            

        
}