<?php

namespace ZawidawieInfo\CoreBundle\Entity;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="ZawidawieInfo\CoreBundle\Model\Repository\ArticleRepository")
 * @ORM\Table(name="articles")
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
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
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_published;

    /**
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    protected $item;

    /**
     * @ORM\OneToMany(targetEntity="ArticlePhotoReference", mappedBy="article")
     */
    protected $relatedPhotos;


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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
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

    /**
     * Set is_published
     *
     * @param boolean $isPublished
     * @return Article
     */
    public function setIsPublished($isPublished)
    {
        $this->is_published = $isPublished;
        return $this;
    }

    /**
     * Get is_published
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * Set item
     *
     * @param ZawidawieInfo\CoreBundle\Entity\Item $item
     * @return Article
     */
    public function setItem(\ZawidawieInfo\CoreBundle\Entity\Item $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * Get item
     *
     * @return ZawidawieInfo\CoreBundle\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function hasPhoto()
    {
        return count($this->relatedPhotos) > 0;
    }

    public function __construct()
    {
        $this->relatedPhotos = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add relatedPhotos
     *
     * @param ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedPhotos
     */
    public function addArticlePhotoReference(\ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedPhotos)
    {
        $this->relatedPhotos[] = $relatedPhotos;
    }

    /**
     * Get relatedPhotos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRelatedPhotos()
    {
        return $this->relatedPhotos;
    }
	
	public function getLeadingPhoto()
    {
      return $this->relatedPhotos[0]->getPhoto();
    }

    /**
     * Add relatedPhotos
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedPhotos
     * @return Article
     */
    public function addRelatedPhoto(\ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedPhotos)
    {
        $this->relatedPhotos[] = $relatedPhotos;
    
        return $this;
    }

    /**
     * Remove relatedPhotos
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedPhotos
     */
    public function removeRelatedPhoto(\ZawidawieInfo\CoreBundle\Entity\ArticlePhotoReference $relatedPhotos)
    {
        $this->relatedPhotos->removeElement($relatedPhotos);
    }
}