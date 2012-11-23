<?php

namespace ZawidawieInfo\CoreBundle\Entity;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="item_attachments")
 */
class ItemAttachment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @Assert\File(maxSize = "10M")
     * ###@Assert\NotBlank()
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="attachments")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=true)
     */
    protected $item;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * #Assert\NotBlank()
     */
    protected $user;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;


    /**
     * @ORM\PrePersist()
     */
    public function preUpload()
    {
        if ($this->file && $this->file instanceof UploadedFile) {
            $this->setPath(sha1(time() * rand(1, 1000)) . '.' . $this->file->guessExtension());
        }
    }

    /**
     * @ORM\PostPersist()
     */
    public function upload()
    {
        if (!$this->file instanceof UploadedFile) {
            return;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does automatically
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getFullPath()) {
            unlink($file);
        }
    }

    public function getFullPath()
    {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/uploads/item_files';
    }


    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set user
     *
     * @param ZawidawieInfo\CoreBundle\Entity\User $user
     */
    public function setUser(\ZawidawieInfo\CoreBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return ZawidawieInfo\CoreBundle\Entity\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime $createdAt
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function hasFile()
    {
        return $this->path != '';
    }
}