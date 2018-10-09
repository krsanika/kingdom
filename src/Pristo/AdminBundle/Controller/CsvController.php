<?php

namespace Pristo\AdminBundle\Controller;

use Pristo\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pristo\FrontBundle\Entity\Cart;
use Pristo\AdminBundle\Entity\Ordered;

class CsvController extends Controller
{
     /**     
     * @Route("/order", name="csv_order")     
     * @Template()
     */    
    public function orderAction()
    {
        $em = $this->getDoctrine()->getManager();
        $flashbag = $this->getRequest()->getSession()->getFlashbag();        
        
        $form = $this->createFormBuilder()
        ->add('submitFile', 'file', array('label' => 'File to Submit'))
        ->getForm();
        $form->handleRequest($this->getRequest());
        // Check if we are posting stuff
        if ($form->isValid()) {
            $file = $form->get('submitFile');
            // Your csv file here when you hit submit button
            $csvs = $this->readCsvFile($file->getData());            
            foreach($csvs as $csv){
//                var_dump($csv);

                // $cart = new Cart();
                // $ordered = new Ordered();
                // $itemData = $this->getDoctrine()->getRepository("PristoAdminBundle:Items")->findWithCode($csv[4]);
                // $cart->setItemId($itemData["item"]->getId());
                // $cart->setSubCate($itemData["subcate"]);
                // $cart->setCount($csv[7]);
                // $em->persist($cart);
                // $em->flush();                
//                $em->persist($ordered);
                $flashbag->add("csv", "success.");
            }
            
        }

        
       
        
        return array('form' => $form->createView(),);
    }
        
    private function readCsvFile($file){
        /*
        
        */
        $csv = array();
        if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
            while(($row = fgetcsv($handle)) !== FALSE) {
                $csv[] = $row;
            }
        }
        unset($csv[0]);        
        return $csv;
    }
    
    
}
