<?php

namespace ZawidawieInfo\CoreBundle\Entity;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use ZawidawieInfo\CoreBundle\Entity\ItemAttachment;
use ZawidawieInfo\CoreBundle\Entity\Photo;

/**
 * @ORM\Entity(repositoryClass="ZawidawieInfo\CoreBundle\Model\Repository\ItemRepository")
 * @ORM\Table(name="items")
 */
class Item
{
    const TYPE_DISTRICT = 1;
    const TYPE_INVESTMENT = 2;
    const TYPE_EVENT = 3;

    public static $typeNames = array(
        self::TYPE_DISTRICT => "Osiedle",
        self::TYPE_INVESTMENT => "Inwestycja",
        self::TYPE_EVENT => "Wydarzenie",
    );

    public static $typeNamesPlural = array(
        self::TYPE_DISTRICT => "Osiedla",
        self::TYPE_INVESTMENT => "Inwestycje",
        self::TYPE_EVENT => "Wydarzenia",
    );

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
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @Gedmo\Slug(separator="-", fields={"name"}, unique=true)
     * @ORM\Column(name="slug", type="string", length=110, unique=true)
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="children")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=true)
     */
    protected $parentItem;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="parentItem")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="ItemAttachment", mappedBy="item")
     */
    private $attachments;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * #Assert\NotBlank()
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Photo")
     * @ORM\JoinColumn(name="latest_photo_id", referencedColumnName="id")
     */
    protected $latestPhoto;

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
     * Set parentItem
     *
     * @param ZawidawieInfo\CoreBundle\Entity\Item $parentItem
     */
    public function setParentItem(\ZawidawieInfo\CoreBundle\Entity\Item $parentItem)
    {
        $this->parentItem = $parentItem;
    }

    /**
     * Get parentItem
     *
     * @return ZawidawieInfo\CoreBundle\Entity\Item $parentItem
     */
    public function getParentItem()
    {
        return $this->parentItem;
    }

    public function hasParentItem()
    {
        return $this->parentItem !== null && $this->parentItem instanceof Item;
    }

    public function getChildren()
    {
        return $this->children;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        return $this->description = $description;
    }

    public function getType()
    {
        return $this->type;
    }

    public static function getTypes()
    {
        return array_keys(self::$typeNames);
    }

    public static function getTypesArray()
    {
        return self::$typeNames;
    }

    public function setType($type)
    {
        return $this->type = $type;
    }

    public function getTypeName()
    {
        return self::$typeNames[$this->type];
    }

    public function getTypeNamePlural()
    {
        return self::$typeNamesPlural[$this->type];
    }

    public function setLatestPhoto($latestPhoto)
    {
        $this->latestPhoto = $latestPhoto;
    }

    public function getLatestPhoto()
    {
        return $this->latestPhoto;
    }

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attachments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add children
     *
     * @param ZawidawieInfo\CoreBundle\Entity\Item $children
     */
    public function addItem(\ZawidawieInfo\CoreBundle\Entity\Item $children)
    {
        $this->children[] = $children;
    }

    /**
     * Add attachments
     *
     * @param ZawidawieInfo\CoreBundle\Entity\ItemAttachment $attachments
     */
    public function addItemAttachment(\ZawidawieInfo\CoreBundle\Entity\ItemAttachment $attachments)
    {
        $this->attachments[] = $attachments;
    }

    /**
     * Get attachments
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Add children
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\Item $children
     * @return Item
     */
    public function addChildren(\ZawidawieInfo\CoreBundle\Entity\Item $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\Item $children
     */
    public function removeChildren(\ZawidawieInfo\CoreBundle\Entity\Item $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Add attachments
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\ItemAttachment $attachments
     * @return Item
     */
    public function addAttachment(\ZawidawieInfo\CoreBundle\Entity\ItemAttachment $attachments)
    {
        $this->attachments[] = $attachments;
    
        return $this;
    }

    /**
     * Remove attachments
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\ItemAttachment $attachments
     */
    public function removeAttachment(\ZawidawieInfo\CoreBundle\Entity\ItemAttachment $attachments)
    {
        $this->attachments->removeElement($attachments);
    }
}