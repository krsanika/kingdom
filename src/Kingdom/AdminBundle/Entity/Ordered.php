<?php

namespace Kingdom\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ordered
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kingdom\AdminBundle\Entity\OrderedRepository")
 */
class Ordered
{
    
    public static $payStatus = array(
        0 => "대기",
        1 => "입금대기",
        2 => "입금완료",
        3 => "제작중",
        4 => "발송준비",
        5 => "발송완료",
    );
    
    public static $channelStatus = array(
        1 => "무통장입금",
        2 => "신용카드",
        3 => "핸드폰"
    );
    
    public function __construct() {
        $now = time();
        $this->created = $now;
        $this->updated = $now;
        $this->isEnabled = true;
        $this->status = 1;
        $this->addressId = null;
        $this->name = "";
        $this->descript = "";
        $this->pay = 0;
        $this->price= 0;
        $this->charge = 0;
        $this->discount =0;
        $this->carts = new ArrayCollection();
        $this->qnas = new ArrayCollection();
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
     * @var User
     * @ORM\ManyToOne(targetEntity="Kingdom\FrontBundle\Entity\User", inversedBy="ordered")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="charge", type="integer")
     */
    private $charge;

    
    /**
     * @var integer
     *
     * @ORM\Column(name="pay", type="integer")
     */
    private $pay;


    /**
     * @var integer
     *
     * @ORM\Column(name="discount", type="integer")
     */
    private $discount;

    

    /**
     * @var integer
     *
     * @ORM\Column(name="channel", type="integer")
     */
    private $channel;

    /**
     * @var integer
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

        /**
     * @var integer
     *
     * @ORM\Column(name="descript",  type="text")
     */
    private $descript;

    /**
     * @var Address
     * @ORM\ManyToOne(targetEntity="Kingdom\FrontBundle\Entity\Address", inversedBy="ordered")
     * @ORM\JoinColumn(name="addressId", referencedColumnName="id")
     */
    private $addressId;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="paymentId", type="integer")
     */
    private $paymentId;

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
     * @ORM\OneToMany(targetEntity="Kingdom\FrontBundle\Entity\Cart", mappedBy="ordered")
     */
    private $carts;
    
    /**
     * @ORM\OneToMany(targetEntity="Qna", mappedBy="orderedId")
s     */
    private $qnas;
    
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
     * @return Ordered
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
     * Set pay
     *
     * @param integer $pay
     * @return Ordered
     */
    public function setPay($pay)
    {
        $this->pay = $pay;

        return $this;
    }
    
    public function getPrice() {
        return $this->price;
    }

    public function getCharge() {
        return $this->charge;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getQnas() {
        return $this->qnas;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function setCharge($charge) {
        $this->charge = $charge;
        return $this;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
        return $this;
    }

    

    /**
     * Get pay
     *
     * @return integer 
     */
    public function getPay()
    {
        return $this->pay;
    }

    /**
     * Set channel
     *
     * @param integer $channel
     * @return Ordered
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return integer 
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Ordered
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set paymentId
     *
     * @param integer $paymentId
     * @return Ordered
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * Get paymentId
     *
     * @return integer 
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return Ordered
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
     * @return Ordered
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
     * @return Ordered
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
    
    function getCarts() {
        return $this->carts;
    }

    function setCarts($carts) {
        $this->carts = $carts;
        return $this;
        
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function getAddressId() {
        return $this->addressId;
    }

    function setAddressId($addressId) {
        $this->addressId = $addressId;
        return $this;
    }


    public function getName() {
        return $this->name;
    }

    public function getDescript() {
        return $this->descript;
    }


    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setDescript($descript) {
        $this->descript = $descript;
        return $this;
    }


    public function setQnas($qnas) {
        $this->qnas = $qnas;
        return $this;
    }

    

}
