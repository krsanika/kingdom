<?php

namespace Kingdom\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kingdom\AdminBundle\Entity\ProductRepository")
 */
class Product
{
    const PAGE_ITEMS = 20;
    
    const WALL_PRINTS = 101;
    const WALL_FRAME = 102;
    const WALL_CANVAS = 103;
    const CASE_PHONE = 201;
    const CASE_TABLET = 202;
    const SKIN_PHONE = 203;
    const SKIN_TABLET = 204;
    const SKIN_LAPTOP = 205;
    const LIFE_MUG = 301;
    const LIFE_RUGS = 302;
    const LIFE_PILLOW = 303;
    const LIFE_CLOCKS = 304;
    const LIFE_CUSHION = 401;
    const LIFE_BAG = 402;
        
            
    public static $genreStr = array(
        1=> "POP art",
        2=> "Character",
        3=> "Nature",
        4=> "Building",
        5=> "Genre1"
    );
    public static $styleStr = array(
       1=>"style1",
       2=>"style2",
       3=>"style3",
       4=>"style4",
       5=>"style5",
       6=>"style6",
       7=>"style7",        
    );
            
    public static $category = array(
        101 => "벽그림",
        102 => "프레임",
        103 => "캔버스",
        201 => "스마트폰",
        202 => "타블렛",
        203 => "갤럭시",
        204 => "베가",
        205 => "노트북",
        301 => "머그컵",
        302 => "토드백",
        303 => "수건",
        304 => "시계",
        401 => "쿠션",
        402 => "가방",       
    );
    
    public static $phone = array(
        1 => "Apple iPhone 5",
        2 => "Apple iPhone 5s/5c",
        3 => "Apple iPhone 6",
        4 => "Apple iPhone 6+",
        5 => "Samsung Galaxy 3/3S",
        6 => "Samsung Galaxy Note 3",
        7 => "Samsung Galaxy Note 2",
        8 => "LG G3 Screen",
        9 => "LG AKA"
    );
    public static $tablet = array(
        1 => "Apple iPad 2",        
        2 => "Apple iPad 3",
        3 => "Asus MeMO Pad",
        4 => "Asus VivoTab",
        5 => "Samsung Galaxy Tab 4",
        6 => "Samsung Galaxy Tab 3",
    );
    
    public static $status = array(
        1 => "판매준비",
        2 => "판매중",
        3 => "판매종료",
    );
    
    public static $price = array(
        101 => 10000,
        102 => 12000,
        103 => 14000,
        201 => 29000,
        202 => 34000,
        203 => 28000,
        204 => 29000,
        205 => 15000,
        301 => 7000,
        302 => 30000,
        303 => 15000,
        304 => 30000,
        401 => 20000,
        402 => 40000,        
    );
    
    public function __construct() { 
        $now = time();
        $this->created = $now;
        $this->updated = $now;
        $this->isEnable = true;
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
     * @ORM\ManyToOne(targetEntity="Kingdom\FrontBundle\Entity\Author", inversedBy="products")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $authorId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="seriesId", type="smallint")
     */
    private $seriesId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="smallint")
     */
    private $num;
        
    
    /**
     * @var integer
     *
     * @ORM\Column(name="genre", type="smallint")
     */
    private $genre;

    /**
     * @var integer
     *
     * @ORM\Column(name="style", type="smallint")
     */
    private $style;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

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
     * @ORM\OneToMany(targetEntity="Items", mappedBy="productId")
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

    public function getAuthorId() {
        return $this->authorId;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getSeriesId() {
        return $this->seriesId;
    }

    public function getNum() {
        return $this->num;
    }
          
        
    public function setAuthorId($authorId) {
        $this->authorId = $authorId;
        return $this;
    }

    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }

    public function setSeriesId($seriesId) {
        $this->seriesId = $seriesId;
        return $this;
    }

    public function setNum($num) {
        $this->num = $num;
        return $this;
    }

    
    
    /**
     * Set genre
     *
     * @param integer $genre
     * @return Product
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return integer 
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set style
     *
     * @param integer $style
     * @return Product
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return integer 
     */
    public function getStyle()
    {
        return $this->style;
    }

    
    /**
     * Set name
     *
     * @param string $name
     * @return Product
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
    
    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
        return $this;
    }

    
    /**
     * Set created
     *
     * @param integer $created
     * @return Product
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
     * @return Product
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
     * @return Product
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
    
    
    
}
