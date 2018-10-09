<?php

namespace Kingdom\AdminBundle\Controller;

use Kingdom\AdminBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Kingdom\AdminBundle\Services\GMemcached;
use Kingdom\FrontBundle\Entity\AuthorImage;
use Kingdom\AdminBundle\Entity\Items;
use Kingdom\AdminBundle\Entity\ItemsFiles;
use Kingdom\AdminBundle\Entity\Product;
use Kingdom\AdminBundle\Form\Type\ItemsType;
use Doctrine\Common\Collections\ArrayCollection;

class AuthorController extends Controller
{
     /**     
     * @Route("/", name="author_index")     
     * @Template()
     */    
    public function indexAction()
    {
        $page = $this->getRequest()->get("page");
        if(empty($page)) $page = 0;
        if(empty($status)) $status = 0;
        
        $start = $page*self::DEFAULT_PAGE;
        $authors = $this->getDoctrine()->getRepository("KingdomFrontBundle:Author")->findForAdmin($start, self::DEFAULT_PAGE);
        foreach($authors as &$author){
            $products = $author->getProducts()->toArray();            
            $author->productCount = 0;
            $author->onSale = 0;
            foreach($products as $product){
                $author->productCount++;
                if($product->getIsEnable()) $author->onSale++;
            }                         
        }

        $pager = $this->getDoctrine()->getRepository("KingdomFrontBundle:Author")->maxPage(self::DEFAULT_PAGE);
        
        return array("authors" => $authors, "pager"=> $pager);
    }
     /**     
     * @Route("/detail/{id}", name="author_detail")     
     * @Template()
     */    
    public function detailAction($id)
    {                
        $author = $this->getDoctrine()->getRepository("KingdomFrontBundle:Author")->find($id);
        $em = $this->getDoctrine()->getManager();
        $flashbag = $this->getRequest()->getSession()->getFlashbag();        

        global $kernel;
        $baseUrl = $this->getRequest()->getScheme()."://".$this->getRequest()->getHttpHost().$this->getRequest()->getBasePath();

        $images = array();
        foreach($author->getProducts() as $product){
            foreach($product->getItems() as $item ){
                $itemPath = "/bundles/Kingdom/image/items/".sprintf("%05d", $author->getId())."/".$product->getNum()."/".$item->getCategory();
                $dir = $kernel->getRootDir()."/../web".$itemPath;
                $files = scandir($dir);
                foreach($files as $file){            
                    if($file == "." || $file == "..") continue;
                    $images[$product->getNum()][$item->getCategory()][] = $baseUrl.$itemPath."/".$file;
                }
            }            
        }
        
        $image = $author->getImage();
        if(empty($image)){
            $image = new AuthorImage();
        }
        

        $imageForm = $this->createFormBuilder($image)
                ->add("file")
                ->add("upload Image", "submit")
                ->getForm();
        $imageForm->handleRequest($this->getRequest());
        
        if($imageForm->isValid()){
//            $data = $imageForm->getData();            
            $image->setAuthor($author);
            $author->setImage($image);
            $image->setUpdated(time());
            $author->setUpdated(time());
            $image->upload();
            $em->persist($image);
            $em->persist($author);
            $em->flush();
            $flashbag->add("image", "success.");
        }
        
        $product = new Product();
        $productForm = $this->createFormBuilder($product)
                ->add("name")                
                ->add("genre", "choice", array(
                    "choices" => array(1=>Product::$genreStr[1],2=>Product::$genreStr[2],3=>Product::$genreStr[3],4=>Product::$genreStr[4], 5=>Product::$genreStr[5],)
                , 'required' => true))                
                ->add("Add Product", "submit")
                ->getForm();
        $productForm->handleRequest($this->getRequest());
        if($productForm->isValid()){
            $maxNum = $this->getDoctrine()->getRepository("KingdomAdminBundle:Product")->getMaxNum($author->getId());
            
            $pData = $productForm->getData();
            $product->setName($pData->getName());
            $product->setGenre($pData->getGenre());
            $product->setAuthorId($author);
            $product->setSeriesId(0);
            $product->setStyle(0);            
            $product->setNum($maxNum+1);
            $em->persist($product);
            $em->flush();            
            $flashbag->add("product", "success.");
            
            $this->redirect($this->generateUrl("author_detail", array("id" => $id)));
        }
                
        $products = $author->getProducts();
        $pArray = null;
        foreach($products as $product){
            $pArray[$product->getId()] = $product->getName();
        }
        $cArray = null;
        $creaters = $this->getDoctrine()->getRepository("KingdomAdminBundle:Creater")->findAll();
        foreach($creaters as $creater){
            $cArray[$creater->getId()] = $creater->getName();
        }
        
        $item = new Items();        
        for($i = 0 ; $i < 3 ; $i++){
            $itemFile[] =  new ItemsFiles();
            $item->getFiles()->add($itemFile[$i]);
        }        
        
        $itemForm = $this->createForm(new ItemsType($pArray, $cArray), $item);
        $itemForm->handleRequest($this->getRequest());
        if($itemForm->isValid()){
            $iData = $itemForm->getData();
            $pId = $itemForm->get("pId")->getData();
            $cId = $itemForm->get("cId")->getData();            
            foreach($products as $product){
                if($product->getId() ==$pId){
                    $item->setProductId($product);
                    break;
                }
            }                                    
            foreach($creaters as $creater){
                if($creater->getId() ==$cId){
                    $item->setCreaterId($creater);
                    break;
                }
            }                                    
            $item->setDescript($iData->getDescript());
            $item->setCategory($iData->getCategory());
            $item->setColors(0);            
            $item->setStatus(1);                                                            
            
            $newFiles = new ArrayCollection();
            $files = $iData->getFiles();  
            $fileInfo = array();
            foreach ($files as $file){                
                if(method_exists($file, "getFile") && method_exists($file->getFile(), "getSize")){
                    //용량검사
                    if($file->getFile()->getSize() > 6000000){
                        $flashbag->add("image", "a File size must under 6MB");                        
                        return array("author" => $author, "images"=>$images, "imageForm"=>$imageForm->createView(), "productForm"=>$productForm->createView(), "itemForm"=>$itemForm->createView());
                     }                    
                    //그림파일 아니면 쳐내기
                if(!strstr($file->getFile()->getMimeType(), "image")){                        
                        $flashbag->add("image", "upload File must Images(JPEG/PNG/etc)");
                        return array("author" => $author, "images"=>$images, "imageForm"=>$imageForm->createView(), "productForm"=>$productForm->createView(), "itemForm"=>$itemForm->createView());
                }
                    
                    $name = sprintf("%05d", $author->getId())."/".$item->getProductId()->getNum()."/".$item->getCategory()."/";
                    $file->setName($name);
                    $file->setItemsId($item);
                    $file->upload();                    
                    $fileInfo[] = array("path"=>$file->getPath(), "name"=>$name);
                    $newFiles->add($file);
                }
            }
            $item->setFiles($newFiles);
            $em->persist($item);
            $em->flush();
            foreach($fileInfo as $info){
                $newFile = $this->getDoctrine()->getRepository("KingdomAdminBundle:ItemsFiles")->findOneBy(array("path"=>$info["path"], "name"=>$info["name"])); 
                $newFile->setName($info["name"]);
                $newFile->setPath($info["path"]);
                $newFile->setItemsId($item);
                $em->persist($newFile);
                $em->flush();
            }
            
            
            
            $flashbag->add("item", "success.");
            $this->redirect($this->generateUrl("author_detail", array("id" => $id)));
        }
        

        
        return array("author" => $author, "images"=>$images, "imageForm"=>$imageForm->createView(), "productForm"=>$productForm->createView(), "itemForm"=>$itemForm->createView());
    }    
    
     /**     
     * @Route("/itemStatus", name="author_changeItemStatus")     
     * @Template()
     */    
    public function changeItemStatusAction()
    {        
        $em = $this->getDoctrine()->getManager();
        $id = $this->getRequest()->get("id");        
        $status = $this->getRequest()->get("status");        
        $authorId = $this->getRequest()->get("authorId");
        $item = $this->getDoctrine()->getRepository("KingdomAdminBundle:Items")->find($id);
        
        $item->setStatus($status);
        $em->persist($item);
        $em->flush();
        return $this->redirect($this->generateUrl("author_detail",  array("id"=>$authorId)));
    }

     /**     
     * @Route("/upload", name="author_upload")
     * @Template()
     */    
    public function uploadAction()
    {        
        $id = $this->getRequest()->get("id");
        $file = $this->getRequest()->get("authorId");
        
        
        $user = $this->getRepository()->find($user);
        
        return $this->redirect($this->generateUrl("author_detail",  array("id"=>$authorId)));
    }    
}
