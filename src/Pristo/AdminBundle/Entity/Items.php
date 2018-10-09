<?php

namespace Pristo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Items
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pristo\AdminBundle\Entity\ItemsRepository")
 */
class Items
{
    
    public function __construct() {
        $now = time();
        $this->buyed = 0;
        $this->favorited = 0;
        $this->created = $now;
        $this->updated = $now;
        $this->isEnabled= true;
        $this->carts = new ArrayCollection();
        $this->files = new ArrayCollection();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer     
     * 
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="items")
     * @ORM\JoinColumn(name="productId", referencedColumnName="id")
     */
    private $productId;

    /**
     * @ORM\ManyToOne(targetEntity="Creater", inversedBy="items")
     * @ORM\JoinColumn(name="createrId", referencedColumnName="id")
     */
    protected $createrId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="smallint")
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="buyed", type="integer")
     */
    private $buyed;

    /**
     * @var string
     *
     * @ORM\Column(name="colors", type="string", length=255)
     */
    private $colors;

    /**
     * @var string
     *
     * @ORM\Column(name="descript", type="text")
     */
    private $descript;

    /**
     * @var integer
     *
     * @ORM\Column(name="favorited", type="integer")
     */
    private $favorited;

    /**
     * @var integer
     *
     * @ORM\Column(name="created", type="integer")
     */
    private $created;

    /**
     * @var integer
     *
     * @ORM\Column(name="updated", type="integer")
     */
    private $updated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isEnabled", type="boolean")
     */
    private $isEnabled;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;


    /**    
     * @ORM\OneToMany(targetEntity="Pristo\FrontBundle\Entity\Cart", mappedBy="itemId", cascade={"persist"})
     */
    protected $carts;
    
   /**
    * @ORM\OneToMany(targetEntity="ItemsFiles", mappedBy="itemsId", cascade={"persist"})
    */    
    protected $files;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     * @return Items
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    public function getCreaterId() {
        return $this->createrId;
    }

    public function setCreaterId($createrId) {
        $this->createrId = $createrId;
        return $this;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return Items
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set buyed
     *
     * @param integer $buyed
     * @return Items
     */
    public function setBuyed($buyed)
    {
        $this->buyed = $buyed;

        return $this;
    }

    /**
     * Get buyed
     *
     * @return integer 
     */
    public function getBuyed()
    {
        return $this->buyed;
    }

    /**
     * Set colors
     *
     * @param string $colors
     * @return Items
     */
    public function setColors($colors)
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Get colors
     *
     * @return string 
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set descript
     *
     * @param string $descript
     * @return Items
     */
    public function setDescript($descript)
    {
        $this->descript = $descript;

        return $this;
    }

    /**
     * Get descript
     *
     * @return string 
     */
    public function getDescript()
    {
        return $this->descript;
    }

    /**
     * Set favorited
     *
     * @param integer $favorited
     * @return Items
     */
    public function setFavorited($favorited)
    {
        $this->favorited = $favorited;

        return $this;
    }

    /**
     * Get favorited
     *
     * @return integer 
     */
    public function getFavorited()
    {
        return $this->favorited;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return Items
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return integer 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param integer $updated
     * @return Items
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return integer 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     * @return Items
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean 
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }
    
    function getStatus() {
        return $this->status;
    }

    function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    function getCarts() {
        return $this->carts;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setCarts($carts) {
        $this->carts = $carts;
        return $this;
    }
    function getFiles() {
        return $this->files;
    }

    function setFiles($files) {
        $this->files = $files;
    }



}
