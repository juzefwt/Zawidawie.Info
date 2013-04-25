<?php

namespace ZawidawieInfo\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="article_photo_reference")
 */
class ArticlePhotoReference
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="photos")
     */
    protected $article;

    /**
     * @ORM\ManyToOne(targetEntity="Photo", inversedBy="relatedArticles")
     */
    protected $photo;

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
     * Set article
     *
     * @param ZawidawieInfo\CoreBundle\Entity\Article $article
     * @return ArticlePhotoReference
     */
    public function setArticle(\ZawidawieInfo\CoreBundle\Entity\Article $article)
    {
        $this->article = $article;
        return $this;
    }

    /**
     * Get article
     *
     * @return ZawidawieInfo\CoreBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set photo
     *
     * @param ZawidawieInfo\CoreBundle\Entity\Photo $photo
     * @return ArticlePhotoReference
     */
    public function setPhoto(\ZawidawieInfo\CoreBundle\Entity\Photo $photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * Get photo
     *
     * @return ZawidawieInfo\CoreBundle\Entity\Photo 
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}