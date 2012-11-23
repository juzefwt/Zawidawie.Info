<?php

namespace ZawidawieInfo\ForumBundle\Entity;
use Herzult\Bundle\ForumBundle\Entity\Category as BaseCategory;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Herzult\Bundle\ForumBundle\Entity\CategoryRepository")
 * @ORM\Table(name="herzult_forum_categories")
 */
class ForumCategory extends BaseCategory
{
    /**
     * @ORM\OneToOne(targetEntity="ZawidawieInfo\ForumBundle\Entity\ForumTopic")
     */
    protected $lastTopic;

    /**
     * @ORM\OneToOne(targetEntity="ZawidawieInfo\ForumBundle\Entity\ForumPost")
     */
    protected $lastPost;
}