<?php

namespace ZawidawieInfo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Comment as BaseComment;
use Symfony\Component\Security\Core\User\UserInterface;
use ZawidawieInfo\CoreBundle\Entity\User;
use FOS\CommentBundle\Model\SignedCommentInterface;
use FOS\CommentBundle\Model\ThreadInterface as Thread;

/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment implements SignedCommentInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="ZawidawieInfo\CoreBundle\Entity\Thread")
     */
    protected $thread;

    /**
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param Thread $thread
     * @return null
     */
    public function setThread(Thread $thread)
    {
        $this->thread = $thread;
    }

    /**
     * Author of the comment
     *
     * @ORM\ManyToOne(targetEntity="ZawidawieInfo\CoreBundle\Entity\User")
     * @var User
     */
    protected $author;

    /**
     * @param User
     */
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get authorName
     * @return string
     */
    public function getAuthorName()
    {
        return $this->getAuthor() ? $this->getAuthor()->getUsername() : 'Nieznany';
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
}