<?php
namespace Kingdom\AdminBundle\Controller;

use Kingdom\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Kingdom\FrontBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class CreateUserController extends Controller{

    
    /**
     * @Route("/list", name="admin_userlist")
     * @Template()
     */
    public function userAction()
    {
        $em = $this->getDoctrine();
        $user_list = $em->getRepository('KingdomFrontBundle:User')
                ->findByIsenabled(1);

        return array('users' => $user_list,);
    }

    /**
     * @Route("/create", name="user_create")
     * @Template()
     */
    public function createAction()
    {
        return $this->onEdit(null);
//        return array();
    }

    /**
     * @Route("/edit/{id}", name="user_edit")
     * @Template()
     */
    public function editAction($id)
    {
        if(empty($id)){
            $id = null;
        }
        
        return $this->onEdit($id);
    }
    
    /**
     * @Route("/new", name="user_new")
     * @Template()
     */
    public function newAction(){
        $em = $this->getDoctrine()->getManager();
//        $flashbag = $this->getRequest()->getSession()->getBag($name)
        $username = $this->getRequest()->get("username");
        $pass = $this->getRequest()->get("password");
        $nick = $this->getRequest()->get("nick");
                
        $duflicate = $em->getRepository('KingdomFrontBundle:User')->findOneByUsername($username);
        if(isset($duflicate)){
            $status = false;
            $msg = "이미 존재하는 메일입니다.";
            $response = new Response(json_encode(array("msg"=> $msg, "status"=>$status)));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $new = new User();
        $new->setUsername($username);
        $new->setFacebookId("");
        $new->setNaverId("");
        $new->setIsEnabled(false);
        $new->setNick($nick);        
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $new->setSalt($salt);
        $ef = $this->get('security.encoder_factory');
        $password = $ef->getEncoder($new)->encodePassword($pass, $salt);
        $new->setPassword($password);
        $em->persist($new);
        $em->flush();
        
        $status = true;
        $msg = "메일을 발송했습니다.";
        $response = new Response(json_encode(array("msg"=> $msg, "status"=>$status)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }
    

    public function onEdit($id = null){
         $em = $this->getDoctrine()->getManager();
         $request = $this->getRequest();
         
         if(isset($id)){
             $user = $em->getRepository('KingdomFrontBundle:User')->find($id);
         } else {
             $user = new User();             
             $user->setId(0);
         }
         
         $form = $this->createFormBuilder($user)                 
                 ->add('nick', 'text', array("required"=> true))
                 ->add('password', 'password', array("required"=> true))
                 ->add('email', 'email', array("required"=> true))
//                 ->add('확인메일을 전송','submit')
                 ->getForm();
         $form->handleRequest($request);
         
         if($form->isValid() ){
            $data = $form->getData();
            $duflicate = $em->getRepository('KingdomFrontBundle:User')->findOneByUsername($data->getEmail());
            if(isset($duflicate)){
                $form->addError(new FormError("ID가 이미 존재합니다."));                    
            }else if(strlen($data->getNick()) < 1){
                $form->addError(new FormError("이름을 입력하세요."));                
            }else if(strlen($data->getPassword()) < 8){
                $form->addError(new FormError("패스워드는 8자 이상입니다."));                
            }else if(!strpos($data->getEmail(), "@")){
                $form->addError(new FormError("메일 주소를 입력하세요"));                
            }else{
                 $user->setUsername($data->getEmail());
                 $user->setEmail($data->getEmail());             
                 $user->setFacebookId("");
                 $user->setNaverId("");             
                 $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
                 $pw_raw = $data->getPassword();
                 $user->setSalt($salt);
                 $user->setIsEnabled(false);
                 $ef = $this->get('security.encoder_factory');
                 $password = $ef->getEncoder($user)->encodePassword($pw_raw, $salt);
                 $user->setPassword($password);
                 $em->persist($user);
                 $em->flush();
                 return $this->redirect($this->generateUrl("user_send", array("id"=>$user->getId())));
           }

        }
        
         return $this->render('KingdomFrontBundle:Login:edit.html.twig',
                 array('user' => $user, 'form' => $form->createView(),));
    }
    
    /**
     * @Route("/send", name="user_send")
     * @Template("")
     */    
    public function sendAction(){
        $request = $this->getRequest();
        $id = $request->get("id");
        $userData = $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->find($id);
        
        
        $mail = \Swift_Message::newInstance()
            ->setSubject('`[프리스토 가입승인메일]')
            ->setFrom("Kingdomregister@generis.jp")
            ->setTo($userData->getUsername())
            ->setBcc("admin@generis.jp")
            ->setBody($this->renderView('KingdomAdminBundle:CreateUser:registmail.html.twig', array('userData' => $userData)));
        
        $this->get('mailer')->send($mail);            
        
        
        return array();
    }


    /**
     * @Route("/confirm", name="user_confirm")
     * @Template("KingdomFrontBundle:Login:sandconfirm.html.twig")
     */
    public function confirmMailAction(){
        $request = $this->getRequest();
        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $userData = $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->find($id);
        $userData->setIsEnabled(true);
        $em->persist($userData);
        $em->flush();
        
        return array();
    }


    /**
     * @Route("/sendpass", name="user_sendpass")
     * @Template("")
     */ 
    public function sendPasswordAction(){
        
        $username = $this->getRequest()->get("username");
        $user = $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->findOneByUsername($username);
        if(empty($user)){
            $msg = "가입된 이메일이 아닙니다.";
            $status = false;
        }else{
            $mail = \Swift_Message::newInstance()
                ->setSubject("[PICker 비밀번호 변경]")
                ->setFrom("Kingdomregister@generis.jp")
                ->setTo($user->getUserName())
                ->setBody($this->renderView('KingdomAdminBundle:CreateUser:passwordmail.html.twig', array('user' => $user)));
            $msg = $user->getUsername()."에 메일을 발송했습니다.";
            $status = true;
            $this->get('mailer')->send($mail);
        }
        
        $response = new Response(json_encode(array("msg"=> $msg, "status"=>$status)));
        $response->headers->set('Content-Type', 'application/json');
        return $response;        
    }
    
    
    /**
     * @Route("/change", name="user_changepass")
     * @Template("")
     */
    public function changePasswordAction(){
        $request = $this->getRequest();
        $flashbag = $request->getSession()->getFlashbag();        
        $id = $request->get("id");
        $password = $request->get("s");
        $em= $this->getDoctrine()->getManager();
        
        $user = $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->find($id);
        
        if($password !== $user->getPassword() ){
            $flashbag->add("change", $user->getEmail()."토큰이 일치하지 않습니다. 다시 시도해 주세요.");
            return $this->redirect($this->generateUrl("login"));                        
        }
        
        $dataset = array("password" => null);
        $form = $this->createFormBuilder($dataset)
             ->add('password', 'password', array("required"=> true))
             ->add('비밀번호 변경','submit')
             ->getForm();
        $form->handleRequest($request);
        
        if($form->isValid() ){
            $data = $form->getData();            
            $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
            $pw_raw = $data["password"];
            $user->setSalt($salt);
            $ef = $this->get('security.encoder_factory');
            $password = $ef->getEncoder($user)->encodePassword($pw_raw, $salt);
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
                    
            $flashbag->add("change", "비밀번호를 변경하였습니다.");            
            return $this->redirect($this->generateUrl("login"));
        }  
        
        return array("form"=> $form->createView(), "user"=>$user);
    }
    
}
