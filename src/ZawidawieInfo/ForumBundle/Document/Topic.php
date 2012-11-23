<?php

namespace ZawidawieInfo\ForumBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Bundle\ForumBundle\Document\Topic as BaseTopic;

/**
 * @MongoDB\Document(
 *   repositoryClass="Bundle\ForumBundle\Document\TopicRepository",
 *   collection="herzult_forum_topic"
 * )
 */
class Topic extends BaseTopic
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="ZawidawieInfo\ForumBundle\Document\Category")
     */
    protected $category;

    /**
     * @MongoDB\ReferenceOne(targetDocument="ZawidawieInfo\ForumBundle\Document\Post")
     */
    protected $firstPost;

    /**
     * @MongoDB\ReferenceOne(targetDocument="ZawidawieInfo\ForumBundle\Document\Post")
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
