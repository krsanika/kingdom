<?php
namespace Kingdom\AdminBundle\Controller;

use Kingdom\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Kingdom\FrontBundle\Entity\Address;

/**
 * Description of AdressController
 *
 * @author kerius
 */
class AddressController extends Controller{
    
     /**
     * @Route("/add", name="address_add")
     * @Template()
     */    
    public function addAddressAction(){
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $name = $request->get("name");
        $pc1 = $request->get("pc1");
        $pc2 = $request->get("pc2");
        $addrStr = $request->get("addrStr");
        $addrDetail = $request->get("addrDetail");
        $userId = $request->get("userId");
        $check =  $request->get("isSelected");
                        
        if(empty($pc1) || empty($pc2) || empty($addrStr) || empty($addrStr) ){
            $request->getSession()->getFlashBag()->set("error", "입력이 완전하지 않습니다.");            
        }else{
            $address = new Address();
            $address->setCodes($pc1.$pc2);
            $address->setName($name);
            $address->setText($addrStr.$addrDetail);
            $address->setUserId($userId);
            $em->persist($address);
            $em->flush();
            $request->getSession()->getFlashBag()->set("address", "success");
        }
        
        if($check[0] == "on"){
            $newAddressId = $address->getId();       
            $user =  $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->find($userId);
            $user->setAddressId($newAddressId);
            $em->persist($user);
            $em->flush();
        }
            
        return $this->redirect($this->generateUrl("mypage_index"));
    }
    
     /**
     * @Route("/remove", name="address_remove")
     * @Template()
     */
    public function removeAddressAction(){
        $id = $this->getRequest()->get("id");                        
        $em = $this->getDoctrine()->getManager();
        $address = $this->getDoctrine()->getRepository("KingdomFrontBundle:Address")->find($id);
        $user =  $this->getDoctrine()->getRepository("KingdomFrontBundle:User")->find($address->getUserId());
        
        if($user->getAddressId() == $id){
            $this->getRequest()->getSession()->getFlashBag()->set("error", "기본 배송지는 삭제할 수 없습니다.");
        }else{
            $em->remove($address);
            $em->flush();            
        }
        
        
        return $this->redirect($this->generateUrl("mypage_index"));
    }
  
    
    
    
}
