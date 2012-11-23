<?php

namespace ZawidawieInfo\ForumBundle\Entity;
use Herzult\Bundle\ForumBundle\Entity\Post as BasePost;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use ZawidawieInfo\CoreBundle\Entity\User as User;

/**
 * @ORM\Entity(repositoryClass="Herzult\Bundle\ForumBundle\Entity\PostRepository")
 * @ORM\Table(name="herzult_forum_posts")
 */
class ForumPost extends BasePost
{
/**
     * @ORM\ManyToOne(targetEntity="ZawidawieInfo\ForumBundle\Entity\ForumTopic")
     */
    protected $topic;

    /**
     * The author name
     *
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    protected $authorName = '';

    /**
     * The author user if any
     *
     * @ORM\ManyToOne(targetEntity="ZawidawieInfo\CoreBundle\Entity\User")
     * @var User
     */
    protected $author = null;

    /**
     * @Assert\MaxLength(10000)
     */
    protected $message;

    public function getId()
    {
	return $this->id;
    }

    public function setId($id)
    {
	$this->id = $id;
    }


    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param  User
     * @return null
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
        $this->authorName = $author->getUsername();
    }

    /**
     * Get authorName
     * @return string
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set authorName
     * @param  string
     * @return null
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;
    }

    public function setMessage($message)
    {
        $this->message = $this->processMessage($message);
    }

    protected function processMessage($message)
    {
        $message = wordwrap($message, 200);

        return $message;
    }
}