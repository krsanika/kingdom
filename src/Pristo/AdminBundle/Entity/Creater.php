<?php

namespace Pristo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Creater
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Creater
{
    public function __construct(){
        $this->items = new ArrayCollection();        
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
     * @ORM\Column(name="category", type="smallint")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="descript", type="text")
     */
    private $descript;
        
    /**
     * @var integer
     *
     * @ORM\Column(name="delprice", type="integer")
     */
    private $delprice;
    
   /**
    * @ORM\OneToMany(targetEntity="Items", mappedBy="createrId")
    */    
    protected $items;

    
    
            
            
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
     * Set category
     *
     * @param integer $category
     * @return Creater
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
     * Set name
     *
     * @param string $name
     * @return Creater
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
     * Set email
     *
     * @param string $email
     * @return Creater
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Creater
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Creater
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set descript
     *
     * @param string $descript
     * @return Creater
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
    
    public function getDelprice() {
        return $this->delprice;
    }

    public function getItems() {
        return $this->items;
    }

    public function setDelprice($delprice) {
        $this->delprice = $delprice;
        return $this;
    }

    public function setItems($items) {
        $this->items = $items;
        return $this;
    }


}
