<?php

namespace Kingdom\FrontBundle\Controller;

use Kingdom\FrontBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Kingdom\FrontBundle\Services\Facebook;
use Kingdom\FrontBundle\Services\Naver;
use Kingdom\FrontBundle\Entity\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class LoginController extends Controller
{    
    /**
     * @Route("/", name="login")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        $username =$request->get("username");
        $password = $request->get("password");
        if(!$username || !$password){            
            $error = true;
            $request->request->set("error", $error);
            return $this->redirect($this->generateUrl("title_index", array("error"=>$error)));
        }
        
        $user = $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->loadUserByUsername($username);
        $error = null;
        if($user){
            $encoderSrv = $this->get("security.encoder_factory");
            $encoder = $encoderSrv->getEncoder($user);
            if ($encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())){
                $token = new UsernamePasswordToken($user, null, "Kingdom_front", $user->getRoles());
                $this->get("security.context")->setToken($token); //now the user is logged in
                //now dispatch the login event
                $event = new InteractiveLoginEvent($request, $token);
                $session->set('_security_Kingdom_front', serialize($token));
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            }else{
                $request->request->set("error", true);
            }
        }else{
                $request->request->set("error", true);
        }
                
        if(isset($error)){
            $request->set("error", true);
            return $this->redirect($this->generateUrl("title_index"));
        }else{
            return $this->redirect($this->generateUrl("product_index"));
        }
        
        
    }
    
    
    
    /**
     * @Route("/facebook", name="login_facebook")
     */
    public function loginFacebookAction(){
        $facebook = new Facebook(Facebook::$config);
        $doctrine = $this->getDoctrine();
        $me = $facebook->api("/me");        
        $user = $doctrine->getRepository("KingdomFrontBundle:User")->findOneByUsername($me["email"]);
                
        if(empty($user)){
            $em = $doctrine->getManager();
            
            $user = new User();
            $user->setUserName($me["email"]);
            $user->setNick($me["name"]);
            $user->setType(User::USER_TYPE_FACEBOOK);
            $user->setFacebookId($me["id"]);
            $user->setNaverId("");
            $user->setImgPath("https://graph.facebook.com/".$me["id"]."/picture");
            $user->setPassword("");
            $user->setSalt("");
            $user->setNick($me["first_name"]);
            $em->persist($user);
            $em->flush();
        }
        
        $user=$this->withoutLogin($user->getUsername());
        return $this->redirect($this->generateUrl("product_index"));
//        return $this->render("KingdomFrontBundle:Login:index", array());
    }

    /**
     * @Route("/naver", name="login_naver")     
     */    
    public function loginNaverAction(){
        $naver = new Naver();
        $state = $this->getRequest()->get("state");
        $code = $this->getRequest()->get("code");
        $sesState = $this->getRequest()->getSession()->get("state");
                                
        // CSRF 방지를 위한 state token 검증 코드
        // 세션 또는 별도의 스토리지에 저장된 state token 과 callback으로 전달받은 state 값이 일치하여야 함
        if($state !== $sesState){
            return $this->redirect($this->generateUrl("title_index"));
        }        
        $result = $naver->loginProcess($this, $code, $state);                            
        
        if(empty($result)){
            return $this->redirect($this->generateUrl("login"));
        }
        
        $this->getRequest()->getSession()->set("nid_".$result["enc_id"], $result);                       
        $doctrine = $this->getDoctrine();        
        $nid = explode("@", $result["email"]);
        $user = $doctrine->getRepository("KingdomFrontBundle:User")->findOneByUsername($result["email"]);
        
        
        if(empty($user)){
            $em = $doctrine->getManager();            
            $user = new User();
            $user->setUserName($result["email"]);
            $user->setType(User::USER_TYPE_NAVER);
            $user->setImgPath($result["profile_image"]);
            $user->setFacebookId("");
            $user->setNick($result["nickname"]);
            $user->setNaverId($nid[0]);            
            $user->setPassword($state);
            $user->setSalt($result["enc_id"]);
            $em->persist($user);
            $em->flush();
        }
        
        $user = $this->withoutLogin($user->getUsername());
        
        return $this->redirect($this->generateUrl("product_index"));            
    }
    
    private function withoutLogin($username){        
        $user = $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->loadUserByUsername($username);

        if (!$user) {
            throw new UsernameNotFoundException("User not found");
        } else {
            $token = new UsernamePasswordToken($user, null, "Kingdom_front", $user->getRoles());
            $this->get("security.context")->setToken($token); //now the user is logged in                 
            //now dispatch the login event
            $request = $this->get("request");            
            $event = new InteractiveLoginEvent($request, $token);
            $this->getRequest()->getSession()->set('_security_Kingdom_front', serialize($token));
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        }               
         return $user;
    }
}
