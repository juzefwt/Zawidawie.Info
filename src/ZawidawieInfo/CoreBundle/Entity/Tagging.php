<?php

namespace ZawidawieInfo\CoreBundle\Entity;

use FPN\TagBundle\Entity\Tagging as BaseTagging;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tagging",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="tagging_idx",columns={"tag_id","resource_type","resource_id"})})
 */
class Tagging extends BaseTagging
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="ZawidawieInfo\CoreBundle\Entity\Tag")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    protected $tag;

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
     * Set tag
     *
     * @param ZawidawieInfo\CoreBundle\Entity\Tag $tag
     * @return Tagging
     */
    public function setTag(\DoctrineExtensions\Taggable\Entity\Tag $tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Get tag
     *
     * @return ZawidawieInfo\CoreBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}