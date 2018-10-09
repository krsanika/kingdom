<?php

namespace Pristo\AdminBundle\Controller;

use Pristo\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pristo\AdminBundle\Services\GMemcached;

class PaymentController extends Controller
{
     /**     
     * @Route("/", name="payment_index")     
     * @Template()
     */    
    public function indexAction()
    {
        $status = $this->getRequest()->get("status");
        $page =  $this->getRequest()->get("page");        
        
        if(empty($status)) $status = 0;
        if(empty($page)) $page = 0;
        $start = $page * self::DEFAULT_PAGE;
        $ordereds = $this->getDoctrine()->getRepository("PristoAdminBundle:Ordered")->findWithCartsList($status, $start, self::DEFAULT_PAGE);
        $pager = $this->getDoctrine()->getRepository("PristoAdminBundle:Ordered")->maxPage($status, self::DEFAULT_PAGE);
        
        return array("orders"=>$ordereds, "pager" =>$pager, "status"=>$status);
    }
    
     /**     
     * @Route("/change", name="payment_change")     
     * @Template()
     */    
    public function changeStatusAction()
    {
        $status = $this->getRequest()->get("status");
        $id = $this->getRequest()->get("id");
        $em = $this->getDoctrine()->getManager();
        
        $ordered = $this->getDoctrine()->getRepository("PristoAdminBundle:Ordered")->find($id);
        $ordered->setStatus($status);
        $ordered->setUpdated(time());
        $em->persist($ordered);
        $em->flush();
        
        return $this->redirect($this->generateUrl("payment_index", array("status" => $status)));
    }    
}
