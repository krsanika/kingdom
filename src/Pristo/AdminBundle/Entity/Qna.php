<?php

namespace Pristo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Qna
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pristo\AdminBundle\Entity\QnaRepository")
 */
class Qna
{
    const STATUS_Q = 1;
    const STATUS_A = 2;
    
    public function __construct() {
        $now = time();
        $this->isReaded = false;
        $this->created = $now; 
        $this->updated = $now;
        $this->isEnabled = true;
        $this->status = self::STATUS_Q;
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
     * @ORM\ManyToOne(targetEntity="Pristo\FrontBundle\Entity\User", inversedBy="qnas")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="text")
     */
    private $context;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isReaded", type="boolean")
     */
    private $isReaded;

    /**
     * @ORM\ManyToOne(targetEntity="Ordered", inversedBy="qnas")
     * @ORM\JoinColumn(name="orderedId", referencedColumnName="id")
     */
    private $orderedId;
     
    
   /**
    * @ORM\OneToMany(targetEntity="QnaFiles", mappedBy="qna", cascade={"persist"})
    */    
    protected $files;
   
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
     * @return Qna
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
     * Set subject
     *
     * @param string $subject
     * @return Qna
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set context
     *
     * @param string $context
     * @return Qna
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }
    
    public function getOrderedId() {
        return $this->orderedId;
    }
    
    public function setOrderedId($orderedId) {
        $this->orderedId = $orderedId;
        return $this;
    }

    
    /**
     * Get context
     *
     * @return string 
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Qna
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
     * Set isReaded
     *
     * @param boolean $isReaded
     * @return Qna
     */
    public function setIsReaded($isReaded)
    {
        $this->isReaded = $isReaded;

        return $this;
    }

    /**
     * Get isReaded
     *
     * @return boolean 
     */
    public function getIsReaded()
    {
        return $this->isReaded;
    }

    
    public function getFiles() {
        return $this->files;
    }

    public function setFiles($files) {
        $this->files = $files;
        return $this;
    }

    
    /**
     * Set created
     *
     * @param integer $created
     * @return Qna
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
     * @return Qna
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
     * @return Qna
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
}
