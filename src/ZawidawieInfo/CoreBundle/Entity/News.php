<?php

namespace ZawidawieInfo\CoreBundle\Entity;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use DoctrineExtensions\Taggable\Taggable;
use Doctrine\Common\Collections\ArrayCollection;
use Nekland\FeedBundle\Item\ItemInterface;

/**
 * @ORM\Entity(repositoryClass="ZawidawieInfo\CoreBundle\Model\Repository\NewsRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="news")
 */
class News implements Taggable, ItemInterface
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
     * @Assert\NotBlank()
     */
    protected $content;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expires_at;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_published;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_accepted;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_short;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $nb_views;

    /**
     * @ORM\ManyToMany(targetEntity="Photo", inversedBy="relatedNews",cascade={"persist"})
     * @ORM\JoinTable(name="news_photo_reference",
     *   joinColumns={@ORM\JoinColumn(name="news_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="photo_id", referencedColumnName="id")}
     * )
     */
    protected $relatedPhotos;

    protected $tags;
    private $tagsString;

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
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setExpiresAt($date)
    {
        $this->expires_at = $date;
    }

    /**
     * Get created_at
     *
     * @return datetime $createdAt
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
    }

    public function getIsPublished()
    {
        return $this->is_published;
    }

    public function setIsPublished($published)
    {
        $this->is_published = $published;
    }

    public function getIsShort()
    {
        return $this->is_published;
    }

    public function setIsShort($published)
    {
        $this->is_published = $published;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
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
        return 'news_tag';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

    public function getNbViews()
    {
        return $this->nb_views;
    }

    public function setNbViews($val)
    {
        $this->nb_views = $val;
    }

    public function incrementNbViews()
    {
        $this->nb_views++;
    }

    public function setIsAccepted($is_accepted)
    {
        $this->is_accepted = $is_accepted;
    }

    public function getIsAccepted()
    {
        return $this->is_accepted;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function __construct()
    {
        $this->relatedPhotos = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function setRelatedPhotos($photos)
    {
        $this->relatedPhotos = $photos;
    }

    public function hasPhoto()
    {
      return count($this->relatedPhotos) > 0;
    }

    public function getLeadingPhoto()
    {
      return $this->relatedPhotos[0];
    }

    /*
     * Return the title of your rss, something like "My blog rss"
     * @return string
     */
    public function getFeedTitle()
    {
	return $this->title;
    }

    /*
     * Return the description of your rss, someting like "This is the rss of my blog about foo and bar"
     * @return string
     */
    public function getFeedDescription()
    {
	$content = strip_tags($this->content);
	return substr($content, 0, strrpos(substr($content, 0, 200), ' ' )) . '...';
    }

    /*
     * Return the route of your item
     * @return array with
     * [0]
     *      =>
     *      	['route']
     *      			=>
     *          			[0] =>  'route_name'
     *          			[1] =>  array of params of the route
     *     	=>
     *      	['other parameter'] => 'content' (you can use for atom)
     * [1]
     *     	=>
     *     		['url'] => 'http://mywebsite.com'
     *     	=>
     *      	['other parameter'] => 'content' (you can use for atom)
     */

    public function getFeedRoutes()
    {
	return array(array('route' => array('news_show', array('slug' => $this->slug))));
    }

    /**
     * @return unique identifiant (for editing)
     */
    public function getFeedId()
    {
	return $this->id;
    }

    /**
     * @abstract
     * @return \DateTime
     */
    public function getFeedDate()
    {
	return $this->created_at;
    }
}