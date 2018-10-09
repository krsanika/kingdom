<?php

namespace Kingdom\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Adress
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Address
{
    
    public function __construct() {
        $now = time();
        $this->created = $now;
        $this->updated = $now;
        $this->isEnable = true;
        $this->ordered = new ArrayCollection();
        $this->count = 1;
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="smallint")
     */
    private $count;

    /**
     * @var string
     *
     * @ORM\Column(name="codes", type="string", length=6)
     */
    private $codes;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;
    
    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="User", inversedBy="addressId")  
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")        
     */
    private $userId;

    
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
     * @ORM\OneToMany(targetEntity="Kingdom\AdminBundle\Entity\Ordered", mappedBy="addressId")
     */
    private $ordered;
    
    
    
    

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
     * Set name
     *
     * @param string $name
     * @return Adress
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Adress
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
     * Set codes
     *
     * @param integer $codes
     * @return Adress
     */
    public function setCodes($codes)
    {
        $this->codes = $codes;

        return $this;
    }

    /**
     * Get codes
     *
     * @return integer 
     */
    public function getCodes()
    {        
        return wordwrap($this->codes, 3, "-", true);
    }

    
    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }

    
    /**
     * Set text
     *
     * @param string $text
     * @return Adress
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Adress
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

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setOrdered($ordered) {
        $this->ordered = $ordered;
        return $this;
    }


}
