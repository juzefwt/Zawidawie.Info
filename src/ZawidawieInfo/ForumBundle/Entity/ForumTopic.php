<?php

namespace ZawidawieInfo\ForumBundle\Entity;
use Herzult\Bundle\ForumBundle\Entity\Topic as BaseTopic;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Herzult\Bundle\ForumBundle\Entity\TopicRepository")
 * @ORM\Table(name="herzult_forum_topics")
 */
class ForumTopic extends BaseTopic
{
    /**
     * @ORM\ManyToOne(targetEntity="ZawidawieInfo\ForumBundle\Entity\ForumCategory")
     */
    protected $category;

    /**
     * @ORM\OneToOne(targetEntity="ZawidawieInfo\ForumBundle\Entity\ForumPost")
     */
    protected $firstPost;

    /**
     * @ORM\OneToOne(targetEntity="ZawidawieInfo\ForumBundle\Entity\ForumPost")
     */
    protected $lastPost;

    /**
     * Get authorName
     * @return string
     */
    public function getAuthorName()
    {
        return $this->getFirstPost()->getAuthorName();
    }

    /**
     * Hack to fix temporary Form issue
     *
     * @param string $message
     * @return void
     */
    public function setMessage($message)
    {
        $this->getFirstPost()->setMessage($message);
    }

    /**
     * Hack to fix temporary Form issue
     *
     * @param string $authorName
     * @return void
     */
    public function setAuthorName($authorName)
    {
        $this->getFirstPost()->setAuthorName($authorName);
    }
}