<?php

namespace ZawidawieInfo\CoreBundle\Model\Repository;

use DoctrineExtensions\Taggable\Taggable;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\ResultSetMapping;

class TagRepository extends ObjectRepository
{
    protected $tagClass = 'ZawidawieInfo\CoreBundle\Entity\Tag';
    protected $taggingClass = 'ZawidawieInfo\CoreBundle\Entity\Tagging';

    public function getEntitiesTaggedWith(Taggable $resource, $tag)
    {
	$query = $this->getObjectManager()
	    ->createQuery("SELECT p FROM ".get_class($resource)." p 
	                   WHERE p.id IN ( 
			      SELECT t.resourceId FROM ".$this->taggingClass." t 
			      JOIN t.tag t2 
			      WHERE t.resourceType = :type AND t2.slug = :slug)")
	    ->setParameter('type', $resource->getTaggableType())
	    ->setParameter('slug', $tag);

	return new Pagerfanta(new DoctrineORMAdapter($query));
    }

    public function getTagsForEntity(Taggable $resource)
    {
	$query = $this->getObjectManager()
	    ->createQuery("SELECT t FROM ".$this->tagClass." t JOIN t.tagging tg
			      WHERE tg.resourceType = :type AND tg.resourceId = :id")
	    ->setParameter('type', $resource->getTaggableType())
	    ->setParameter('id', $resource->getId());

	return $query->getResult();
    }

    public function getTagsStringForEntity(Taggable $resource)
    {
	$tags = array();
	$list = $this->getTagsForEntity($resource);

	foreach ($list as $item)
	    $tags[] = $item->getName();

	return implode(',', $tags);
    }

    public function findOneBySlug($slug)
    {
        return $this->findOneBy(array('slug' => $slug));
    }
}
