<?php

namespace ZawidawieInfo\CoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_queries")
 */
class CatalogQuery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $query;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $score;

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
     * Set query
     *
     * @param string $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * Get query
     *
     * @return string $query
     */
    public function getQuery()
    {
        return $this->query;
    }

    public function __toString()
    {
        return $this->query;
    }

    /**
     * Set score
     *
     * @param string $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Get score
     *
     * @return string $score
     */
    public function getScore()
    {
        return $this->score;
    }

    public function incrementScore()
    {
	$this->score++;
    }
}