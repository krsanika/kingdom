<?php

namespace Pristo\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Pristo\AdminBundle\Services\GMemcached;
use Pristo\AdminBundle\Entity\Product;
use Symfony\Component\Security\Core\SecurityContext;
use Pristo\FrontBundle\Services\Facebook;
use Pristo\FrontBundle\Services\Naver;
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
            $rankings =$this->getDoctrine()->getRepository("PristoAdminBundle:Product")->buyRanking($start, Product::PAGE_ITEMS);
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
            $products = $this->getDoctrine()->getRepository("PristoAdminBundle:Items")->findWithProducts($ids);            
            GMemcached::set(GMemcached::PREFIX_ITEMS.$page, $products);
        }        
        return $products;
    }
        
    public function findItemForAll(){
        $products = GMemcached::get(GMemcached::PREFIX_ALL);
        if(empty($products)){
            $products = $this->getDoctrine()->getRepository("PristoAdminBundle:Product")->findAll();
            $ids = array();
            foreach($products as $product){
                $ids[] = $product->getId();
            }
            $products = $this->getDoctrine()->getRepository("PristoAdminBundle:Items")->findWithProducts($ids);
            GMemcached::set(GMemcached::PREFIX_ALL, $products);
        }     
        
        return $products;
    }

    public function loginInformation()
    {                   
        $error = null;
        $request = $this->getRequest();
        $session = $request->getSession();
                
        $msg =null;
        if(isset($error)){
            $msg = $request->get("error");
        }
        
        $facebook = new Facebook(Facebook::$config);
        $loginUrl = $facebook->getLoginUrl(Facebook::$param);
        $session = $this->getRequest()->getSession();
        
        $naver = new Naver();
        $naverState = $naver->generateState();
        $session->set("state", $naverState);
        
        $products =$this->findItemForAll();
                
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        // Sessionにエラー情報があるか確認
        } elseif ($session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            // Sessionからエラー情報を取得
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            // 一度表示したらSessionからは削除する
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }       
        return array(
            "products"=> $products,
            "facebookUrl"=>$loginUrl,
            'naverState' => $naverState,
            'naverAppId' => $naver->getConfig("client"),
            "ref"=> $this->getRefImage(),            
            "msg" => $msg,
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,            
            );               
    }
    public function getRefImage(){
        $refs = array();
        global $kernel;
        $refPath = "bundles/kingdom/image/author/ref/";
        $dir = $kernel->getRootDir()."/../web/".$refPath;
        $files = scandir($dir);
        foreach($files as $file){
            if($file == "." || $file == "..") continue;
            $refs[] = $refPath.$file;
        }
        $m= (int)date("i", time());
        $one = (int)floor(60/count($refs));
        $t = 0;
        $iter = -1;
        while($t <= $m){
            $t += $one;
            $iter++;
        };        
        return $refs[$iter];
    }
}

