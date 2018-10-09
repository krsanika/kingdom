<?php

namespace Kingdom\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ItemsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemsRepository extends EntityRepository
{
    
    public function findWithProducts($ids){
        $qb = $this->createQueryBuilder("i")                
                ->select("i.id as iId, i.category, i.buyed, i.colors, i.descript, i.favorited")
                ->addSelect("p.id as pId, p.num, p.name, p.seriesId, p.genre, p.style, p.created, p.updated")
                ->addSelect("a.id as authorId")
                ->addSelect("u.id as userId, u.nick")
                ->innerJoin("KingdomAdminBundle:Product", "p", "WITH", "i.productId = p.id")
                ->leftJoin("p.authorId", "a")
                ->leftJoin("a.userId", "u")
                ->where("p.id IN(:ids)")
                ->andWhere("p.isEnable = 1")
                ->andWhere("i.isEnabled = 1")
                ->setParameter("ids", array_values($ids))
                ->getQuery();
        $results = $qb->getArrayResult();
        
        $avg = $this->getAvgSales();                
                
        $products = array();        
        foreach($results as $result){ 
            $pid = $result["pId"];
            $products[$pid]["id"] = $result["pId"];            
            $products[$pid]["name"] = $result["name"];            
            $products[$pid]["authorId"] = $result["authorId"];
            $products[$pid]["nick"] = $result["nick"];            
            $products[$pid]["num"] = $result["num"];
            $products[$pid]["name"] = $result["name"];
            $products[$pid]["seriesId"] = $result["seriesId"];
            $products[$pid]["genre"] = $result["genre"];
            $products[$pid]["style"] = $result["style"];
            $products[$pid]["created"] = $result["created"];
            $products[$pid]["updated"] = $result["updated"];
            
            $products[$pid]["items"][$result["category"]] = array(
                "id" => $result["iId"],
                "category" => $result["category"],
                "descript" => $result["descript"],
                "buyed" => $result["buyed"],
                "favorited" =>$result["favorited"],
                "colors" => $result["colors"],);      
            
            if(!array_key_exists("tBuyed", $products[$result["pId"]])){
                $products[$pid]["tBuyed"] = 0;
            } 
            if(!array_key_exists("tFavorited", $products[$result["pId"]])){
                $products[$pid]["tFavorited"] = 0;
            } 
           $products[$pid]["tBuyed"] += $result["buyed"];                        
           $products[$pid]["tFavorited"] += $result["favorited"];
           }

       foreach($products as &$product){
           $seeds = 2;                      
            if( $product["tBuyed"] > $avg) $seeds = 4;           
           $product["frame"] = rand(1, $seeds);           
        }
        
        shuffle($products);
        
        return $products;
    }

    public function getAvgSales(){
        $qb = $this->createQueryBuilder('i')
                ->select("AVG(i.buyed) as avgSales")
                ->innerJoin("KingdomAdminBundle:Product", "p", "WITH", "i.productId = p.id")
                ->where("i.isEnabled = 1")
                ->andWhere("p.isEnable = 1")
                ->getQuery();
        try{
            $result = $qb->getSingleResult();
        }  catch (\NoResultException $e){
            return null;
        }        
        return floor($result["avgSales"]);
        
    }
        
    
    public function findForDetail($id){
        $qb =  $this->createQueryBuilder("i")         
                ->select("i, p, a, c, u")
                ->leftJoin("i.productId", "p")
                ->leftJoin("p.authorId", "a")
                ->leftJoin("i.createrId", "c")
                ->leftJoin("a.userId", "u")
//                ->join("KingdomAdminBundle:Product", "p", "WITH", "i.productId = p.id")
//                ->innerJoin("KingdomFrontBundle:Author", "a", "WITH", "p.authorId = a.id")
                ->where("i.id = :id")
                ->andWhere("p.isEnable = 1")
                ->andWhere("i.isEnabled = 1")
                ->andWhere("a.isEnabled = 1")
                ->setParameter("id", $id)
                ->getQuery();
        try{
            $result = $qb->getArrayResult();
        }  catch (\NoResultException $e){
            return null;
        }        
                        
        return $result[0];
    }
    
    public function findOthers($iId, $pId, $aId){
        $otherIds = array();
        $otherIds[] = $iId;
        $result = array();
        $qb1 =  $this->createQueryBuilder("i")         
                ->select("i.id, i.category, a.id as authorId, p.num")
                ->leftJoin("i.productId", "p")
                ->leftJoin("p.authorId", "a")
                ->where("p.id = :pId")
                ->andWhere("i.id != :iId")
                ->andWhere("p.isEnable = 1")
                ->andWhere("i.isEnabled = 1")
                ->andWhere("a.isEnabled = 1")
                ->setParameters(array("pId" => $pId, "iId"=> $iId))
                ->getQuery();
        $others = $qb1->getArrayResult();
        foreach($others as $other){
            $otherIds[] = $other["id"];
            $result["other"][] = $other;
        }            
        $qb2 =  $this->createQueryBuilder("i")         
                ->select("i.id, i.category,  a.id as authorId, p.num")
                ->leftJoin("i.productId", "p")
                ->leftJoin("p.authorId", "a")
                ->where("a.id = :aId")
                ->andWhere("i.id not IN(:ids)")
                ->andWhere("p.isEnable = 1")
                ->andWhere("i.isEnabled = 1")
                ->andWhere("a.isEnabled = 1")
                ->setParameters(array("aId" => $aId, "ids"=>  array_values($otherIds)))
                ->getQuery();
        $authors = $qb2->getArrayResult();
        foreach($authors as $author){            
            $result["author"][] = $author;
        }
        
                
        return $result;
        
    }
    
    public function findOtherAuthorId($id){
    }
        
    public function findWithCode($code){
        $authorId = (int)substr($code, 0, 5);
        $num = (int)substr($code, 5, 1);
        $cate = (int)substr($code, 6, 3);
        $subcate = (int)substr($code, -1);
        var_dump($authorId, $num, $cate, $subcate);
        $qb = $this->createQueryBuilder('i')                
                ->innerJoin("KingdomAdminBundle:Product", "p", "WITH", "i.productId = p.id")
                ->where("i.isEnabled = 1")
                ->andWhere("p.isEnable = 1")
                ->andWhere("p.num = :num")
                ->andWhere("p.authorId = :authorId")
                ->andWhere("i.category = :cate")                
                ->setParameter("cate", $cate)
                ->setParameter("authorId", $authorId)
                ->setParameter("num", $num)                               
                ->getQuery();
        try{
            $result = $qb->getSingleResult();
        }  catch (\NoResultException $e){
            return null;
        }                
        
        return array(
            "item" =>$result, 
            "authorId" => $authorId,
            "num" => $num,
            "cate"=>$cate,
            "subcate" =>$subcate,
            );
    }
}
