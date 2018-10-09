<?php

namespace Kingdom\FrontBundle\Controller;

use Kingdom\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Kingdom\AdminBundle\Services\GenerisMemcached;
use Symfony\Component\Form\FormError;
use Kingdom\AdminBundle\Entity\Qna;
use Kingdom\AdminBundle\Entity\QnaFiles;
use Kingdom\AdminBundle\Form\Type\QnaType;
use Doctrine\Common\Collections\ArrayCollection;
use Kingdom\FrontBundle\Services\Facebook;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Kingdom\AdminBundle\Entity\Ordered;
use Kingdom\AdminBundle\Entity\Product;
use Kingdom\FrontBundle\Entity\Address;


class MypageController extends Controller
{

     /**     
     * @Route("/", name="mypage_index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $id = $this->getRequest()->get("id");
        if(isset($id)){
            $user = $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->find($id);
        }                                  
        $facebook = new Facebook(Facebook::$config);
        $facebook->setFileUploadSupport(true); 

        $me = null;
        $session = $this->getRequest()->getSession();
        $fUser = $facebook->getUser();
        if ($fUser) {
          try {
            $me = $facebook->api('/me');
          } catch (FacebookApiException $e) {
            error_log($e);
          }
        }
        $myAddress = $this->getDoctrine()->getRepository("KingdomFrontBundle:Address")->findByUserId($user->getId());
        $myQna = $this->getDoctrine()->getRepository("KingdomAdminBundle:Qna")->findBy(array('userId'=> $user->getId()), array('updated' => 'DESC') );

        
        $flashbag = $this->getRequest()->getSession()->getFlashbag();        
        
        
        //Add Qna
        $qna = new Qna();
        $qnaFile =  new QnaFiles();
        $qna->getFiles()->add($qnaFile);

        $qnaForm = $this->createForm(new QnaType(), $qna);
                
        $qnaForm->handleRequest($this->getRequest());
        
        if($qnaForm->isValid()){
            $data = $qnaForm->getData();
            $qna->setSubject($data->getSubject());
            $qna->setUserId($this->getUser());
            $qna->setContext($data->getContext());
            $ordered = $this->getDoctrine()->getRepository("KingdomAdminBundle:Ordered")->find($qnaForm->get("orderId")->getData());
            $qna->setOrderedId($ordered);
            $newFiles = new ArrayCollection();
            $files = $data->getFiles();
            $filePath = array();
            foreach ($files as $file){
                if(method_exists($file, "getFile") && method_exists($file->getFile(), "getSize")){
                    //용량검사
                    if($file->getFile()->getSize() > 6000000){
                        $flashbag->add("error", "a File size must under 6MB");
                        return array("qnaForm"=>$qnaForm->createView() );
                    }
                    
                    //그림파일 아니면 쳐내기
                if(!strstr($file->getFile()->getMimeType(), "image")){
                        $flashbag->add("error", "upload File must Images(JPEG/PNG/etc)");
                        return array("qnaForm"=>$qnaForm->createView() );
                }
                    $name = $this->getUser()->getId()."/".$data->getOrderId();
                    $file->setName($name);
                    $file->upload();
                    $filePath[] = $file->getPath();                    
                    $newFiles->add($file);
                }
            }            
            $qna->setFiles($newFiles);
            $em->persist($qna);
            $em->flush();                        
            foreach($filePath as $path){
                if(isset($path)){
                    $newFile = $this->getDoctrine()->getRepository("KingdomAdminBundle:QnaFiles")->findOneBy(array("name"=>$this->getUser()->getId()."/".$data->getOrderId(), "path"=> $path));
                    $newFile->setQna($qna);
                    $em->persist($newFile);
                    $em->flush();
                        
                }
            }
                     
            $flashbag->add("qna", "success.");
        }
        
        
        
        return array("user"=> $user,/* "author"=>$author,*/ "addresses" => $myAddress, "count" => count($myAddress), "myQnas"=>$myQna, "qnaForm"=>$qnaForm->createView());
    }
        
     /**
     * @Route("/change", name="mypage_change")
     * @Template()
     */    
    public function changeAction(){
        $request = $this->getRequest();        
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $flashbag = $this->getRequest()->getSession()->getFlashbag();
                 
        $passForm = $this->createFormBuilder($user) 
            ->add('nick', 'text', array("required"=> true))
            ->add('password', 'password', array("required"=> true))
            ->add('confirm', 'password', array("required"=> true, 'mapped' => false))
            ->add('변경','submit')
            ->getForm();
         $passForm->handleRequest($request);
         
         if($passForm->isValid()){
             $passData = $passForm->getData();             
             if($passData->getPassword() != $passForm->get("confirm")->getData()){
                $passForm->addError(new FormError("비밀번호가 일치하지 않습니다.."));                
             }else{
                $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
                $pw_raw = $passData->getPassword();
                $user->setNick($passData->getNick());
                $user->setSalt($salt);                
                $ef = $this->get('security.encoder_factory');
                $password = $ef->getEncoder($user)->encodePassword($pw_raw, $salt);
                $user->setPassword($password);                
                $em->persist($user);                
                $em->flush();                 
                $flashbag->add("change", "success.");
             }
         }
         
        return array("user" => $user, /*"nickForm"=>$nickForm->createView(),*/ "passForm"=>$passForm->createView());
    }
    
    
     /**
     * @Route("/address", name="mypage_address")
     * @Template()
     */    
    public function addressAction(){
        $addressId = $this->getRequest()->get("id");
        $em = $this->getDoctrine()->getManager();
        $user =$this->getUser();
        $user->setAddressId($addressId);
        $em->persist($user);
        $em->flush();
        return $this->redirect($this->generateUrl("mypage_index"));
    }

    
     /**
     * @Route("/qna", name="mypage_qna")
     * @Template()
     */    
    public function qnaAction(){
        $id = $this->getRequest()->get("id");
        $qna = $this->getDoctrine()->getRepository("KingdomAdminBundle:Qna")->find($id);
        
        if($this->getUser()->getId() != $qna->getUserId()->getId()){
            return new Response(null);
        }
        
        if(!$qna->getIsReaded()){
            $em = $this->getDoctrine()->getManager();
            $qna->setIsReaded(true);
            $em->persist($qna);
            $em->flush();
        }
        $qnaArray = array(
            "subject" => $qna->getSubject(),
            "date" => $qna->getUpdated(),
            "context" => $qna->getContext(),
            "orderId" => $qna->getOrderedId()->getId(),
            "files" => array(),
        );
        foreach($qna->getFiles() as $file){
            $qnaArray["files"][] = array( "name" => $file->getName(), "path" => $file->getWebPath());
        }                                
        $response = new Response(json_encode($qnaArray));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
    
    
     /**
     * @Route("/pay", name="mypage_pay")
     * @Template()
     */    
    public function paymentAction(){
        $id = $this->getUser()->getId();
        $ordereds = $this->getDoctrine()->getRepository("KingdomAdminBundle:Ordered")->findBy(array("userId"=>$id), array("updated" => "DESC"));
        
        return array("ordereds"=>$ordereds);
    }

     /**
     * @Route("/order", name="mypage_ordered")
     * @Template()
     */    
    public function orderedAction(){
        $id = $this->getRequest()->get("id");
        $order = $this->getDoctrine()->getRepository("KingdomAdminBundle:Ordered")->find($id);    
        return array("order"=>$order);
    }
    
     /**
     * @Route("/cart", name="mypage_cart")
     * @Template()
     */    
    public function cartAction(){
        $id = $this->getUser()->getId();
        
        $carts = $this->getDoctrine()->getRepository("KingdomFrontBundle:Cart")->findBy(array("userId"=>$id, "ordered"=>null), array("updated" => "DESC"));
                
        return array("carts" => $carts);
    }
    
     /**
     * @Route("/delcart", name="mypage_delCart")
     * @Template()
     */    
    public function delCartAction(){
        $ids = $this->getRequest()->get("ids");        
        $em = $this->getDoctrine()->getManager();
        foreach($ids as $id){
            $cart = $this->getDoctrine()->getRepository("KingdomFrontBundle:Cart")->find($id);
            $em->remove($cart);            
        }                                
        $em->flush();
        
        $result = array("msg" => "삭제되었습니다.");
        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
     /**
     * @Route("/buyInput", name="mypage_buyInput")
     * @Template()
     */        
    public function buyInputAction(){
        $strs = $this->getRequest()->get("strs");
        $carts = array();
        $em = $this->getDoctrine()->getManager();        
        foreach($strs as $key=>$value){
            $cart = $this->getDoctrine()->getRepository("KingdomFrontBundle:Cart")->find($key);
            if($cart->getCount() != $value){
                $cart->setCount($value);
                $em->persist($cart);
                $em->flush();                
            }
            $carts[] = $cart;
        }
        
        $total = 0;
        foreach($carts as $cart){
            $total += Product::$price[$cart->getItemId()->getCategory()] * $cart->getCount();
        }  
        $charge = 0;
        if($total < 7000){
            $charge = 7000;
        }
        
        
//        $adrForm = $this->createForm($address)
                
        
        return array("carts" => $carts, "total"=>$total, "charge"=>$charge);
    }
    
     /**
     * @Route("/buyProcess", name="mypage_buyProcess")
     * @Template()
     */        
    public function buyProcessAction(){
        $name = $this->getRequest()->get("name");
        $pcode = $this->getRequest()->get("pcode");
        $adrStr = $this->getRequest()->get("adrStr");
        $method = $this->getRequest()->get("method");
        $pay = $this->getRequest()->get("pay");
        $phone = $this->getRequest()->get("phone");
        $descript = $this->getRequest()->get("descript");        
        $cartIds = $this->getRequest()->get("cartIds");        
        $isSame = $this->getRequest()->get("isSame");
        $discount = $this->getRequest()->get("discount");
        $em = $this->getDoctrine()->getManager();             
        if(!$isSame){
            $address = new Address();
            $address->setCodes($pcode);
            $address->setName($name);
            $address->setPhone($phone);
            $address->setText($adrStr);        
            $address->setUserId($this->getUser());
            $em->persist($address);
            $em->flush();            
        }else{
            $address = $this->getUser()->getAddressId();
        }
        if($discount > 0){            
            $user = $this->getUser();
            $point = $user->getPoint()-$discount;
            $user->setPoint($point);
            $em->persist($user);
            $em->flush();
        }
                        
        $ordered = new Ordered();
        $ordered->setAddressId($address);
        $ordered->setCharge(0);
        if($pay < 7000){ 
            $ordered->setCharge(7000);
        };
        $ordered->setName($name);
        $ordered->setChannel($method);
        $ordered->setDescript($descript);
        $ordered->setDiscount(0);
        $ordered->setPrice($pay);
        $ordered->setPaymentId(0);
        $ordered->setPay($pay);
        $ordered->setStatus(2);
        $ordered->setUserId($this->getUser());
        $em->persist($ordered);
        $em->flush();

        $carts = $this->getDoctrine()->getRepository("KingdomFrontBundle:Cart")->findForBuying($cartIds);
        foreach($carts as $cart){
            $cart->setOrdered($ordered);
            $em->persist($ordered);
        };        
        $em->flush();
        
        
        $result = array("result" => true);
        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
     /**
     * @Route("/buyEnd", name="mypage_buyEnd")
     * @Template()
     */        
    public function buyEndAction(){
        
        return array();                
    }
}
