<?php

namespace Kingdom\FrontBundle\Controller;

use Kingdom\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Kingdom\FrontBundle\Services\Facebook;
use Kingdom\FrontBundle\Services\Naver;


/*
 *  入り口コントローラー。今は仮ログインにつなげます。
 */
class TitleController extends Controller
{
    /**
     * @Route("/", name="title_index")
     * @Template("")
     */
    public function indexAction()
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
        $refPath = "bundles/Kingdom/image/author/ref/";
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
