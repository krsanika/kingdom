<?php
namespace Kingdom\FrontBundle\Twig;

use Symfony\Component\HttpFoundation\Request;
use Kingdom\AdminBundle\Entity\Product;
use Kingdom\AdminBundle\Entity\Ordered;

class KingdomExtension extends \Twig_Extension
{
    
    
    //Requestを使う為の処理
    protected $request;
    protected $environment;

    public function setRequest(Request $request = null){
        $this->request = $request;
    }
    public function initRuntime(\Twig_Environment $environment){
        $this->environment = $environment;
    }

    public function getFilters() {
        return array(
           new \Twig_SimpleFilter("sprintf", array($this, 'sprintf')),
           new \Twig_SimpleFilter("timeToDate", array($this, 'timeToDate')),
           new \Twig_SimpleFilter("codes", array($this, 'codes')),
           new \Twig_SimpleFilter("category", array($this, 'category')),
           new \Twig_SimpleFilter("phone", array($this, 'phone')),
           new \Twig_SimpleFilter("tablet", array($this, 'tablet')),
           new \Twig_SimpleFilter("price", array($this, 'price')),
           new \Twig_SimpleFilter("itemStatus", array($this, 'itemStatus')),
           new \Twig_SimpleFilter("channelStatus", array($this, 'channelStatus')),
           new \Twig_SimpleFilter("payStatus", array($this, 'payStatus')),
           new \Twig_SimpleFilter("getItem", array($this, 'getItem')), 
        );        
    }
    
    public function getFunctions(){
        return array(
            'getControllerName' => new \Twig_Function_Method($this, 'getControllerName', array('is_safe'=> array('html'))),
            'getActionName' => new \Twig_Function_Method($this, 'getActionName', array('is_safe'=> array('html'))),
            'thumbFile' => new \Twig_Function_Method($this, 'thumbFile', array('is_safe'=> array('html'))),            
        );
    }
    
    public function thumbFile($authorId, $num, $category){
        global $kernel;
        $baseUrl = $this->request->getScheme()."://".$this->request->getHttpHost().$this->request->getBasePath();
        $itemPath = "/bundles/Kingdom/image/items/";
        $dir = sprintf("%05d", $authorId)."/".$num."/".$category;        
        
        
        
        if(file_exists($kernel->getRootDir()."/../web/".$itemPath.$dir."/thumb.jpg")){
            $thumb =$baseUrl.$itemPath.$dir."/thumb.jpg";
        }else{
            $thumb =$baseUrl.$itemPath."not.png";
        }
        return $thumb;
        
    }
    
    // コントローラー名取得    
    public function getControllerName()
    {
        if(null !== $this->request)
        {
            $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
            $matches = array();
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return strtolower($matches[1]);
        }

    }

    // アクション名取得    
    public function getActionName()
    {
        if(null !== $this->request)
        {
            $pattern = "#::([a-zA-Z]*)Action#";
            $matches = array();
            preg_match($pattern, $this->request->get('_controller'), $matches);

            return $matches[1];
        }
    }
    public function timeToDate($time){
        
        $date = date($time, "y/M/D H:i");
        
        return $date;
    }
        
    public function sprintf($int, $str){
        return sprintf($str, $int);
    }

    public function category($id){
        return Product::$category[$id];
    }

    public function phone($id){
        return Product::$phone[$id];
    }

    public function tablet($id){
        return Product::$tablet[$id];
    }

    public function price($id){
        return Product::$price[$id];
    }
    
    public function codes($authorId, $num, $category){
        return sprintf("%05d", $authorId).(string)$num.(string)$category;
    }
    
    public function itemStatus($id){        
        return Product::$status[$id];
    }
    
    public function channelStatus($id){        
        return Ordered::$channelStatus[$id];
    }
    
    public function payStatus($id){        
        return Ordered::$payStatus[$id];
    }
    //Product.php 에서 array $item을 받아와 반환하는 트위그  
    public function getItem(){
        return Product::$item[$id];
    }
    
    //abstract用
    public function getName()
    {
        return 'Kingdom_extension';
    }    
    
    
}