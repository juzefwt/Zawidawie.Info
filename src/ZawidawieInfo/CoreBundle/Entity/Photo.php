<?php

namespace ZawidawieInfo\CoreBundle\Entity;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DoctrineExtensions\Taggable\Taggable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="ZawidawieInfo\CoreBundle\Model\Repository\PhotoRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="photos")
 */
class Photo implements Taggable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @Gedmo\Slug(separator="-", fields={"title"}, unique=true)
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @Assert\File(maxSize = "2M", mimeTypes = {
     *   "image/png",
     *   "image/pjpeg",
     *   "image/jpeg",
     *   "image/tiff"
     * })
     * ###@Assert\NotBlank()
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $path;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $item;

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

    protected $tags;

    private $tagsString;

    /**
     * @ORM\OneToMany(targetEntity="ArticlePhotoReference", mappedBy="photo")
     */
    protected $relatedArticles;

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
        return __DIR__ . '/../../../../web/uploads/photos';
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getTags()
    {
        $this->tags = $this->tags ? : new ArrayCollection();
        return $this->tags;
    }

    public function getTagsString()
    {
        return $this->tagsString;
    }

    public function setTagsString($tags)
    {
        $this->tagsString = $tags;
    }

    public function getTaggableType()
    {
        return 'photo_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

    public function __construct()
    {
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     * @return Photo
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     * @return Photo
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function hasRelatedArticles()
    {
        return count($this->relatedArticles) > 0;
    }

    /**
     * Add relatedArticles
     *
     * @param ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedArticles
     */
    public function addArticlePhotoReference(\ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedArticles)
    {
        $this->relatedArticles[] = $relatedArticles;
    }

    /**
     * Get relatedArticles
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRelatedArticles()
    {
        return $this->relatedArticles;
    }

    /**
     * Add relatedArticles
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedArticles
     * @return Photo
     */
    public function addRelatedArticle(\ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedArticles)
    {
        $this->relatedArticles[] = $relatedArticles;
    
        return $this;
    }

    /**
     * Remove relatedArticles
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedArticles
     */
    public function removeRelatedArticle(\ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedArticles)
    {
        $this->relatedArticles->removeElement($relatedArticles);
    }
}