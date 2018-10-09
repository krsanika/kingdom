<?php

namespace Kingdom\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Kingdom\AdminBundle\Services\GMemcached;
use Kingdom\AdminBundle\Entity\Product;
/*
 * 上書き用コントローラー。Symfony基本コントローラーじゃなくてこっちを継承してね。
 * 作者：くるさにか
 */

class Controller extends BaseController
{                    
    
    public function getProducts($page){
        $products = GMemcached::get(GMemcached::PREFIX_PAGE.$page);
        if(empty($products)){
            $products = $this->findItemForDisplay($page);
            GMemcached::set(GMemcached::PREFIX_PAGE.$page, $products);            
        }
        return $products;
    }

    //キャッシュを確認して取ってくる
//    private function confirmPlayer(){
//       $mPlayer = null;
//       global $kernel;
//       $kernel->getContainer()-
//           $token = $kernel->getContainer()->get("security.context")->getToken();
//           $doctrine = $kernel->getContainer()->get("doctrine");
//       if(method_exists($token, "getUser")){
//            //まずはMemcacheから取れるか確認        
//            $mPlayer = EsocialMemcached::get(EsocialMemcached::PREFIX_PLAYER.$token->getUser()->getId());
//            //無かったら入れる
//            if(empty($mPlayer)){                
//                $mPlayer = $doctrine->getRepository('AbyssAlphaBundle:Player')->findOneByUserId($token->getUser()->getId());
//                GMemcached::set(EsocialMemcached::PREFIX_PLAYER.$mPlayer->getId(), $mPlayer);
//            } 
//       }
//        return $mPlayer;
//    }
        
    
    private function findItemForDisplay($page){
        $start = $page*Product::PAGE_ITEMS;
        
        $rankings = GMemcached::get(GMemcached::PREFIX_RANKING);
        if(empty($rankings)){
            $rankings =$this->getDoctrine()->getRepository("KingdomAdminBundle:Product")->buyRanking($start, Product::PAGE_ITEMS);
            GMemcached::set(GMemcached::PREFIX_RANKING, $rankings);
        }                    
        $ids = array();        
        $totalBuyed = array();
        foreach($rankings as $ranking){
            $ids[] = $ranking["id"];
        }
        foreach($rankings as $ranking){
            $totalBuyed[$ranking["id"]] = $ranking["totalBuyed"];
        }
        $products = GMemcached::get(GMemcached::PREFIX_ITEMS.$page);
        if(empty($products)){
            $products = $this->getDoctrine()->getRepository("KingdomAdminBundle:Items")->findWithProducts($ids);            
            GMemcached::set(GMemcached::PREFIX_ITEMS.$page, $products);
        }        
        return $products;
    }
        
    public function findItemForAll(){
        $products = GMemcached::get(GMemcached::PREFIX_ALL);
        if(empty($products)){
            $products = $this->getDoctrine()->getRepository("KingdomAdminBundle:Product")->findAll();
            $ids = array();
            foreach($products as $product){
                $ids[] = $product->getId();
            }
            $products = $this->getDoctrine()->getRepository("KingdomAdminBundle:Items")->findWithProducts($ids);
            GMemcached::set(GMemcached::PREFIX_ALL, $products);
        }
        return $products;
    }
    
}
