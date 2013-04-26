<?php

namespace ZawidawieInfo\CoreBundle\Entity;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\GeographicalBundle\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="ZawidawieInfo\CoreBundle\Model\Repository\CatalogItemRepository")
 * @ORM\Table(name="catalog_items")
 * @Vich\Geographical(on="update")
 */
class CatalogItem
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
     * @Gedmo\Slug(separator="-", fields={"name"}, unique=true)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="CatalogKeyword", inversedBy="catalogItems",cascade={"persist"})
     * @ORM\JoinTable(name="catalog_item_keywords",
     *   joinColumns={@ORM\JoinColumn(name="catalog_item_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="catalog_keyword_id", referencedColumnName="id")}
     * )
     */
    protected $keywords;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
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
     * @ORM\Column(type="decimal", scale=7)
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", scale=7)
     */
    protected $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $www;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(maxSize = "1M", mimeTypes = {
     *   "image/png",
     *   "image/pjpeg",
     *   "image/jpeg",
     *   "image/gif"
     * })
     */
    protected $photo;

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
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text $description
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Set tel
     *
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * Get tel
     *
     * @return string $tel
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set fax
     *
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * Get fax
     *
     * @return string $fax
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set mail
     *
     * @param string $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * Get mail
     *
     * @return string $mail
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set www
     *
     * @param string $www
     */
    public function setWww($www)
    {
        $this->www = $www;
    }

    /**
     * Get www
     *
     * @return string $www
     */
    public function getWww()
    {
        return $this->www;
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

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Notice the latitude property must have a setter
     */
    public function setLatitude($value)
    {
        $this->latitude = $value;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Notice the longitude property must have a setter
     */
    public function setLongitude($value)
    {
        $this->longitude = $value;
    }

    /**
     * @Vich\GeographicalQuery
     *
     * This method builds the full address to query for coordinates.
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getKeywordsCount()
    {
        return count($this->keywords);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->keywords = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add keywords
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\CatalogKeyword $keywords
     * @return CatalogItem
     */
    public function addKeyword(\ZawidawieInfo\CoreBundle\Entity\CatalogKeyword $keywords)
    {
        $this->keywords[] = $keywords;
    
        return $this;
    }

    /**
     * Remove keywords
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\CatalogKeyword $keywords
     */
    public function removeKeyword(\ZawidawieInfo\CoreBundle\Entity\CatalogKeyword $keywords)
    {
        $this->keywords->removeElement($keywords);
    }
}