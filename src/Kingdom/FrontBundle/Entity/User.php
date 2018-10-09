<?php

namespace Kingdom\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kingdom\FrontBundle\Entity\UserRepository")
 */
class User implements AdvancedUserInterface
{
    const USER_TYPE_ALREADY = 0;
    const USER_TYPE_EMAIL = 1;
    const USER_TYPE_FACEBOOK = 2;
    const USER_TYPE_NAVER = 3;
    
    public function __construct() {
        $now = time();
        $this->role = "ROLE_USER";
        $this->created = $now; 
        $this->updated = $now;
        $this->isEnabled = true;
        $this->point = 0;
        $this->type=1;
        $this->imgPath = "";
        $this->addressId = null;
        $this->buyed = 0;
        $this->ordered = new ArrayCollection();
        $this->author = null;
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
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nick", type="string", length=16)
     */
    private $nick;
    /**
     * @var integer
     *
     * @ORM\Column(name="point", type="integer")
     */
    private $point;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255)
     */
    private $facebookId;

    /**
     * @var string
     *
     * @ORM\Column(name="naverId", type="string", length=255)
     */
    private $naverId;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=16)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="imgPath", type="string", length=255)
     */
    private $imgPath;

        
    /**
     * @var integer
     *
     * @ORM\Column(name="buyed", type="integer")
     */
    private $buyed;
    
    /**    
     * @ORM\OneToOne(targetEntity="Address", mappedBy="userId")
     */
    private $addressId;
        
    
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
     * @ORM\OneToOne(targetEntity="Author", mappedBy="userId")
     */
    protected $author;
    
    /**    
     * @ORM\OneToMany(targetEntity="Kingdom\AdminBundle\Entity\Ordered", mappedBy="userId")
     */
    protected $ordered;

/**
    * @ORM\OneToMany(targetEntity="Kingdom\AdminBundle\Entity\Qna", mappedBy="userId")
    */    
    protected $qnas;
       
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    
    /**
     * Set type
     *
     * @param integer $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }

    function getOrdered() {
        return $this->ordered;
    }

    function setOrdered($ordered) {
        $this->ordered = $ordered;
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }


    public function getNick() {
        return $this->nick;
    }

    public function setNick($nick) {
        $this->nick = $nick;
        return $this;
    }

    /**
     * Set point
     *
     * @param integer $point
     * @return User
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return integer 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set facebookId
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set naverId
     *
     * @param string $naverId
     * @return User
     */
    public function setNaverId($naverId)
    {
        $this->naverId = $naverId;

        return $this;
    }

    /**
     * Get naverId
     *
     * @return string 
     */
    public function getNaverId()
    {
        return $this->naverId;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    
    public function getImgPath() {
        return $this->imgPath;
    }

    public function setImgPath($imgPath) {
        $this->imgPath = $imgPath;
        return $this;
    }

    public function getAddressId() {
        return $this->addressId;
    }

    public function setAddressId($addressId) {
        $this->addressId = $addressId;
        return $this;
    }

    
    function getBuyed() {
        return $this->buyed;
    }

    function setBuyed($buyed) {
        $this->buyed = $buyed;
        return $this;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return User
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
     * @return User
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
     * @return User
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
    
    public function eraseCredentials() {
        return true;
    }

    public function getRoles() {
        return array($this->role);
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->isEnabled;
    }
    
    
}
