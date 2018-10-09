<?php

namespace Pristo\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * QnaFiles
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class QnaFiles
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

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
     * @ORM\ManyToOne(targetEntity="Qna", inversedBy="files", cascade={"persist"})
     * @ORM\JoinColumn(name="qnaId", referencedColumnName="id", nullable=false)
     */
    protected $qna;

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
     * Set name
     *
     * @param string $name
     * @return QnaFiles
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
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }
    
   protected function getUploadRootDir()
    {
        // アップロードされたファイルを保存する場所への絶対パス
        return __DIR__.'/../../../../web/bundles/kingdom/image/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // ビューで アップロードされたファイルを参照する際のために __DIR__ を取り除く
        return '/qna/upload/'.$this->name;
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
    public function setQna($qna)
    {
        $this->qna = $qna;
        return $this;
    }

    /**
     * Get qnaId
     *
     * @return integer 
     */
    public function getQna()
    {
        return $this->qna;
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
    
    public function upload()
    {
        // フィールドが必須でなければ、ファイルプロパティが空でも受け付けます
        if (null === $this->file) {
            return;
        }

        // ここではオリジナルの名前を使用します
        // しかし、セキュリティの対処のため、サニタイズはしてください

        // move メソッドは、対象となるディレクトリを受け取り、ファイルを移動します
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

        // パスのプロパティには、ファイルの保存先をセットします
        $this->path = $this->file->getClientOriginalName();

        // もう必要無いので、ファイルのプロパティを片付けます
        $this->file = null;
    }    
    
}
