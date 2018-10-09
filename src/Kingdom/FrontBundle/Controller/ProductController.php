<?php

namespace Kingdom\FrontBundle\Controller;

use Kingdom\FrontBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use  Kingdom\AdminBundle\Services\GMemcached;
use Symfony\Component\HttpFoundation\Response;
use Kingdom\FrontBundle\Entity\Cart;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Description of ProductController
 *
 * @author kerius
 */

class ProductController extends Controller{
    
    const ENTITY = "KingdomAdminBundle:Product";
    
     /**
     * @Route("/", name="product_index")
     * @Template()
     */
    public function indexAction()
    {
        $quick = $this->getRequest()->get("quick");
        $page = $this->getRequest()->get("page");
        if(empty($page)) $page = 0;        
//        $products = $this->getDoctrine()->getRepository("KingdomAdminBundle:Product")->findForView(0);
        $products =$this->getProducts($page);
                        
        return array("products"=> $products, "quick" => $quick);
    }        

     /**
     * @Route("/add", name="product_add")     
     */
    public function addAction(){
        $page = $this->getRequest()->get("page");
        if(empty($page)) $page =1;        
        $products = $this->getProducts($page);        
        
        $response = new Response(json_encode($products));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }        
    
     /**
     * @Route("/detail/{id}", name="product_detail")     
     * @Template()      
     */
    public function detailAction($id = null){
        if(empty($id)){
            return $this->redirect($this->generateUrl("product_index"));
        }
        
        $item = GMemcached::get(GMemcached::PREFIX_DETAIL.$id);
        if(empty($item)){
            $item = $this->getDoctrine()->getRepository("KingdomAdminBundle:Items")->findForDetail($id);
            $authorImage = $this->getDoctrine()->getRepository("KingdomFrontBundle:AuthorImage")->find($item["productId"]["authorId"]["id"]);

    //        $creater = $this->getDoctrine()->getRepository("KingdomAdminBundle:Creater")->findOneByCategory($item["category"]);
            $item = $this->getAllImagePath($item);
            $item["authorImage"] = $authorImage->getWebPath();

            $other = $this->getDoctrine()->getRepository("KingdomAdminBundle:Items")->findOthers($item["id"], $item["productId"]["id"], $item["productId"]["authorId"]["id"]);
            $item["other"] = $other;
            GMemcached::set(GMemcached::PREFIX_DETAIL.$id, $item);
        }

//        $response = new Response(json_encode($item));
//        $response->headers->set('Content-Type', 'application/json');
        
        return array("item" => $item);
    }
    
    private function getAllImagePath($item){
        global $kernel;
        $baseUrl = $this->getRequest()->getScheme()."://".$this->getRequest()->getHttpHost().$this->getRequest()->getBasePath();                
        $itemPath = "/bundles/Kingdom/image/items/".sprintf("%05d", $item["productId"]["authorId"]["id"])."/".$item["productId"]["num"]."/".$item["category"];
        
        $dir = $kernel->getRootDir()."/../web".$itemPath;
        $files = scandir($dir);
        
        foreach($files as $file){
            if($file == "." || $file == "..") continue;
            $item["allImgPath"][] = $baseUrl.$itemPath."/".$file;
        }
                       
        return $item;
        
    }
    
   /**
     * @Route("/cart", name="product_addCart")
     */
    public function cartAction(){
        $id = $this->getRequest()->get("id");
        $subcate =$this->getRequest()->get("subcate");        
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository("KingdomAdminBundle:Items")->find($id);
        $cart = new Cart();
        $cart->setCount(1);
        $cart->setSubCate($subcate);
        $cart->setUserId($this->getUser()->getId());
        $cart->setOrdered(null);
        $cart->setItemId($item);
        $em->persist($cart);      
        $em->flush();
        
        $response = new Response(json_encode(array("msg"=> "카트에 담았습니다.", "cartId" => $cart->getId())));
        $response->headers->set('Content-Type', 'application/json');
        return $response;        
    }    
    
}
