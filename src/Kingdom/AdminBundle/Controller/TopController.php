<?php

namespace Kingdom\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Kingdom\AdminBundle\Services\GenerisMemcached;

class TopController extends Controller
{
     /**     
     * @Route("/", name="adminTop_index")     
     * @Template()
     */    
    public function indexAction()
    {
        
       
        
        return array();
    }
}
