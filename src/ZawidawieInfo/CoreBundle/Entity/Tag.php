<?php

namespace ZawidawieInfo\CoreBundle\Entity;

use FPN\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="ZawidawieInfo\CoreBundle\Model\Repository\TagRepository")
 * @ORM\Table(name="tag")
 */
class Tag extends BaseTag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="ZawidawieInfo\CoreBundle\Entity\Tagging", mappedBy="tag", fetch="EAGER")
     */
    protected $tagging;

    public function __construct($name=null)
    {
        $this->setName($name);
        $this->setCreatedAt(new \DateTime('now'));
        $this->setUpdatedAt(new \DateTime('now'));

        $this->tagging = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add tagging
     *
     * @param ZawidawieInfo\CoreBundle\Entity\Tagging $tagging
     */
    public function addTagging(\ZawidawieInfo\CoreBundle\Entity\Tagging $tagging)
    {
        $this->tagging[] = $tagging;
    }

    /**
     * Get tagging
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTagging()
    {
        return $this->tagging;
    }

    /**
     * Remove tagging
     *
     * @param \ZawidawieInfo\CoreBundle\Entity\Tagging $tagging
     */
    public function removeTagging(\ZawidawieInfo\CoreBundle\Entity\Tagging $tagging)
    {
        $this->tagging->removeElement($tagging);
    }
}