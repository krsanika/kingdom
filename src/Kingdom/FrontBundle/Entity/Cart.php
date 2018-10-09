<?php

namespace Kingdom\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kingdom\FrontBundle\Entity\CartRepository")
 */
class Cart
{
    
    public function __construct() {
        $now = time();
        $this->count = 0;
        $this->subcate = 1;
        $this->created = $now;
        $this->updated = $now;
        $this->isEnable = true;        
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
     * @ORM\Column(name="userId", type="integer")
     */
    private $userId;

    /**
     * @var Items
     * @ORM\ManyToOne(targetEntity="Kingdom\AdminBundle\Entity\Items", inversedBy="carts")
     * @ORM\JoinColumn(name="itemId", referencedColumnName="id",  nullable=false)
     */    
    private $itemId;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="smallint")
     */
    private $count;

    /**
     * @var integer
     *
     * @ORM\Column(name="subcate", type="smallint")
     */
    private $subcate;

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
     * @ORM\Column(name="isEnable", type="boolean")
     */
    private $isEnable;

    
    /**
     * @var Ordered
     * @ORM\ManyToOne(targetEntity="Kingdom\AdminBundle\Entity\Ordered", inversedBy="carts")
     * @ORM\JoinColumn(name="orderedId", referencedColumnName="id")
     */    
    protected $ordered;

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
     * Set userId
     *
     * @param integer $userId
     * @return Cart
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set itemId
     *
     * @param Items $itemId
     * @return Cart
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
        return $this;
    }

    /**
     * Get itemId
     *
     * @return Item
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Cart
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set status
     *
     * @param integer $subcate
     * @return Cart
     */
    public function setSubCate($subcate)
    {
        $this->subcate = $subcate;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getSubCate()
    {
        return $this->subcate;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return Cart
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
     * @return Cart
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
     * Set isEnable
     *
     * @param boolean $isEnable
     * @return Cart
     */
    public function setIsEnable($isEnable)
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    /**
     * Get isEnable
     *
     * @return boolean 
     */
    public function getIsEnable()
    {
        return $this->isEnable;
    }
    
    function getOrdered() {
        return $this->ordered;
    }

    function setOrdered($ordered) {
        $this->ordered = $ordered;
        return $this;
        
    }


}
