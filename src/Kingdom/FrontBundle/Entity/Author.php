<?php

namespace Kingdom\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Author
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kingdom\FrontBundle\Entity\AuthorRepository")
 */
class Author
{
    const LEGION_KOREA = 1;
    const LEGION_JAPAN = 2;
    const LEGION_USA = 3;
    const LEGION_ENGLAND = 4;
    const LEGION_FRANCE = 5;
    const LEGION_GERMEN = 6;
    const LEGION_CHINA = 7;
    const LEGION_SWISS = 8;
    const LEGION_ITALY = 9;
            
    public static $legionStr = array( null,
        "Korea","Japan","U.S.A","England","France","Germen","China","Swiss","Italy",
    );
            
    public function __construct() {
        $now = time();
        $this->profit = 0;
        $this->created = $now;
        $this->updated = $now;
        $this->isEnabled = true;     
        $this->region = 1;
        $this->products = new ArrayCollection();
        $this->descript = "Please change this descript.";
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
     * @ORM\OneToOne(targetEntity="User", inversedBy="author")  
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")   
     */
    private $userId;

        
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="descript", type="text")
     */
    private $descript;

    /**
     * @var integer
     *
     * @ORM\Column(name="region", type="smallint")
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="profit", type="integer")
     */
    private $profit;


    /**
     * @var integer
     *
     * @ORM\Column(name="monthly", type="integer")
     */
    private $monthly;
    
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
     * @var integer
     *
     * @ORM\Column(name="isEnabled", type="integer")
     */
    private $isEnabled;

    /**
     * @ORM\OneToMany(targetEntity="Kingdom\AdminBundle\Entity\Product", mappedBy="authorId")
     */
    protected $products;
    
    /**
     * @ORM\OneToOne(targetEntity="AuthorImage", mappedBy="author")     
     */
    protected $image;
    
    
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
     * @return Author
     */
    public function setUserId($user)
    {
        $this->userId = $user;

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
     * Set name
     *
     * @param string $name
     * @return Author
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
     * Set descript
     *
     * @param string $descript
     * @return Author
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
     * Set region
     *
     * @param integer $region
     * @return Author
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return integer 
     */
    public function getRegion()
    {
        return $this->region;
    }

    
    /**
     * Set profit
     *
     * @param integer $profit
     * @return Author
     */
    public function setProfit($profit)
    {
        $this->profit = $profit;

        return $this;
    }

    /**
     * Get profit
     *
     * @return integer 
     */
    public function getProfit()
    {
        return $this->profit;
    }

    function getMonthly() {
        return $this->monthly;
    }

    function setMonthly($monthly) {
        $this->monthly = $monthly;
        return $this;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return Author
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
     * @return Author
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
     * @param integer $isEnabled
     * @return Author
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return integer 
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }
    
    function getProducts() {
        return $this->products;
    }

    function setProducts($products) {
        $this->products = $products;
        return $this;
    }

    function getImage() {
        return $this->image;
    }

    function setImage($image) {
        $this->image = $image;
    }



}
