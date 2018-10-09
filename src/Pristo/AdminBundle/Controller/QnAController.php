<?php

namespace Pristo\AdminBundle\Controller;

use Pristo\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pristo\AdminBundle\Services\GenerisMemcached;
use Pristo\AdminBundle\Entity\Qna;
use Pristo\AdminBundle\Entity\QnaFiles;
use Pristo\AdminBundle\Form\Type\QnaType;
use Doctrine\Common\Collections\ArrayCollection;

class QnAController extends Controller
{
     /**     
     * @Route("/", name="qna_index")     
     * @Template()
     */    
    public function indexAction()
    {
        
        $page = $this->getRequest()->get("page");
        $userId = $this->getRequest()->get("userId");        
        if(empty($page)) $page = 0;        
        $start = $page*self::DEFAULT_PAGE;
        $qnas = $this->getDoctrine()->getRepository("PristoAdminBundle:Qna")->findForAdmin($userId, $start, self::DEFAULT_PAGE);        
        $pager = 0;
        if($userId == 0){
            $pager = $this->getDoctrine()->getRepository("PristoAdminBundle:Qna")->maxPage(self::DEFAULT_PAGE);            
        }
        
        return array("qnas"=> $qnas, "pager" =>$pager);
    }
    
     /**     
     * @Route("/delete/{id}", name="qna_delete")     
     * @Template()
     */    
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qna =$this->getDoctrine()->getRepository("PristoAdminBundle:Qna")->find($id);
        $em->remove($qna);
        $em->flush();
        
        return $this->redirect($this->generateUrl("qna_index"));
    }
    
     /**     
     * @Route("/detail/{id}", name="qna_detail")     
     * @Template()
     */    
    public function detailAction($id){
        $qna =$this->getDoctrine()->getRepository("PristoAdminBundle:Qna")->find($id);
        
        if($qna->getIsReaded() ==false && $qna->getStatus()){
            $em = $this->getDoctrine()->getManager();
            $qna->setIsReaded(true);
            $em->persist($qna);
            $em->flush();            
        }
        
        return array("qna" => $qna);
    }
    
     /**     
     * @Route("/add/{userId}/{orderedId}", name="qna_add")     
     * @Template()
     */    
    public function addAction($userId, $orderedId){
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $flashbag = $this->getRequest()->getSession()->getFlashbag();
        
        $user =$this->getDoctrine()->getRepository("PristoFrontBundle:User")->find($userId);
        $order =$this->getDoctrine()->getRepository("PristoAdminBundle:Ordered")->find($orderedId);
        
        $qna = new Qna();
        $qna->setOrderedId($order);
        $qna->setUserId($user);
        $qnaFile = array();
        
        for($i = 0 ; $i < 3 ; $i++){
            $qnaFile[] =  new QnaFiles();
            $qna->getFiles()->add($qnaFile[$i]);
        }        

        $qnaForm = $this->createForm(new QnaType(), $qna);
                
        $qnaForm->handleRequest($request);
                  
        if($qnaForm->isValid()){
            $data = $qnaForm->getData();
            $qna->setSubject($data->getSubject());
            $qna->setContext($data->getContext());
            $qna->setStatus(2);
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
                    $file->upload();                    $filePath[] = $file->getPath();                    
                    $newFiles->add($file);
                }
            }            
            $qna->setFiles($newFiles);
            $em->persist($qna);
            $em->flush();                        
            foreach($filePath as $path){
                if(isset($path)){
                    $newFile = $this->getDoctrine()->getRepository("PristoAdminBundle:QnaFiles")->findOneBy(array("name"=>$this->getUser()->getId()."/".$data->getOrderId(), "path"=> $path));
                    $newFile->setQna($qna);
                    $em->persist($newFile);
                    $em->flush();
                        
                }
            }
                     
            $flashbag->add("qna", "success.");
        }
        
        
        

        return array("qnaForm"=>$qnaForm->createView() );                        
    }
    
}
