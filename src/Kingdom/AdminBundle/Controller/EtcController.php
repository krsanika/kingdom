<?php

namespace Kingdom\AdminBundle\Controller;

use Kingdom\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Kingdom\AdminBundle\Services\GMemcached;
use Kingdom\AdminBundle\Entity\Product;

class EtcController extends Controller
{
    
     /**     
     * @Route("/", name="etc_index")     
     * @Template()
     */    
    public function indexAction()
    {
        global $kernel;
        $defImages = array();
        $forms = array();
        $webPath = "bundles/Kingdom/image/items/default/";        
        foreach(Product::$category as $key => $value){
            $dir = $kernel->getRootDir()."/../web/".$webPath.$key."/";            
            $files = array_diff(scandir($dir), array('.', '..'));
            $form = $this->createFormBuilder()
                       ->add('image', 'file')
                        ->add("Add Image", "submit")
                       ->getForm();

            $form->handleRequest($this->getRequest());
            $defImages[] =array("category" => $key, "str"=>$value, "files"=>$files, "form"=>$form->createView());
        }
        
        if ($form->isValid()) {
             $data = $form->getData();
             $file = $data["image"];
             $name = $file->getClientOriginalName();                            
             $file->move($dir, $name) ;             
             $this->redirect($this->generateUrl("etc_index", array("webPath" => $webPath, "defImages"=> $defImages)));
         }
        
        return array("webPath" => $webPath, "defImages"=> $defImages);
    }
    

}
