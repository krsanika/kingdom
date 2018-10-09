<?php

namespace Kingdom\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * QnaFiles
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class AuthorImage
{
    
    public function __construct() {    
        $now = time();        
        $this->created = $now; 
        $this->updated = $now;
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

   /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;
    
    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="Author", inversedBy="image")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id", nullable=false)
     */
    protected $author;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return QnaFiles
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

 public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->author->getId().".".$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().$this->path;
    }
    
   protected function getUploadRootDir()
    {
        // アップロードされたファイルを保存する場所への絶対パス
        return __DIR__.'/../../../../web/bundles/Kingdom/image/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // ビューで アップロードされたファイルを参照する際のために __DIR__ を取り除く
        return '/author/profile/';
    }
    
    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
        return $this;
    }

    
    /**
     * Set qnaId
     *
     * @param integer $qnaId
     * @return QnaFiles
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get qnaId
     *
     * @return integer 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return QnaFiles
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
     * @return QnaFiles
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
    
    /*
     * @ORM\prePersist()
     * @ORM\preUpdate()
     */
    public function preUpload(){
        if(null !== $this->file){
            $this->path = $this->file->guessExtension();
        }
    }
    
    /*
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */    
    public function upload()
    {
        // フィールドが必須でなければ、ファイルプロパティが空でも受け付けます
        if (null === $this->file) {
            return;
        }

        // パスのプロパティには、ファイルの保存先をセットします
        $this->path = $this->getAuthor()->getId().".".$this->file->guessExtension();
        
        // move メソッドは、対象となるディレクトリを受け取り、ファイルを移動します
        $this->file->move($this->getUploadRootDir(), $this->getAuthor()->getId().".".$this->file->guessExtension());
       
        // もう必要無いので、ファイルのプロパティを片付けます
        unset($this->file);
    }
    
    /*
     * @ORM\PostRemove()
     */    
    public function removeUpload(){
        if($file = $this->getAbsolutePath()) {
            unlink($file);
        }        
    }
}
