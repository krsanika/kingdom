<?php

namespace Pristo\AdminBundle\Controller;

use Pristo\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pristo\AdminBundle\Services\GMemcached;
use Pristo\FrontBundle\Entity\User;
use Pristo\FrontBundle\Entity\Author;

class UserController extends Controller
{
     /**     
     * @Route("/", name="user_index")     
     * @Template()
     */    
    public function indexAction()
    {
        $page = $this->getRequest()->get("page");
        $order = $this->getRequest()->get("order");
        
        
        $asc = "ASC";
        if(empty($page)) $page = 0;
        if(empty($order)) $order = "nick";
        if($order != "nick") $asc = "DESC";;        
        
        $start = $page*self::DEFAULT_PAGE;
        
        $users = $this->getDoctrine()->getRepository("PristoFrontBundle:User")->findForAdmins($start, self::DEFAULT_PAGE, $order, $asc);
        $pager = $this->getDoctrine()->getRepository("PristoFrontBundle:User")->maxPage(self::DEFAULT_PAGE);
                
        return array("users"=> $users, "pager"=> $pager);
    }

     /**     
     * @Route("/activate/{id}", name="user_activate")     
     */        
    public function activateUserAction($id){
        $em = $this->getDoctrine()->getManager();
        $user =  $this->getDoctrine()->getRepository("PristoFrontBundle:User")->find($id);
        $user->setIsEnabled(true);
        $em->persist($user);
        $em->flush();
        
        return $this->redirect($this->generateUrl("user_index"));
    }

     /**     
     * @Route("/deactivate/{id}", name="user_deactivate")     
     * @Template()
     */        
    public function deactivateUserAction($id){    
        $em = $this->getDoctrine()->getManager();
        $user =  $this->getDoctrine()->getRepository("PristoFrontBundle:User")->find($id);
        $em->remove($user);
        $em->flush();
        
        return $this->redirect($this->generateUrl("user_index"));
    }

     /**     
     * @Route("/addAuthor/{id}", name="user_addauthor")     
     * @Template()
     */            
    public function addAuthorAction($id){
        $em = $this->getDoctrine()->getManager();
        $user =  $this->getDoctrine()->getRepository("PristoFrontBundle:User")->find($id);
       
        $author = new Author();        
//        $author->setUserId($id);
        $author->setUserId($user);
        $author->setName($user->getNick());
        $em->persist($author);
        $em->flush();
        
        return $this->redirect($this->generateUrl("user_index"));
    }

}
