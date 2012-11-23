<?php

namespace ZawidawieInfo\ForumBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Bundle\ForumBundle\Document\Category as BaseCategory;

/**
 * @MongoDB\Document(
 *   repositoryClass="Bundle\ForumBundle\Document\CategoryRepository",
 *   collection="herzult_forum_category"
 * )
 */
class Category extends BaseCategory
{
    /**
     * @MongoDB\ReferenceOne(targetDocument="ZawidawieInfo\ForumBundle\Document\Topic")
     */
    protected $lastTopic;

    /**
     * @MongoDB\ReferenceOne(targetDocument="ZawidawieInfo\ForumBundle\Document\Post")
     */
    protected $lastPost;
}
