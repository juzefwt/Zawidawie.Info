<?php

namespace ZawidawieInfo\CoreBundle\Model\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use ZawidawieInfo\CoreBundle\Entity\Item;

class PhotoRepository extends ObjectRepository
{
    public function findPk($id)
    {
        return $this->findOneBy(array('id' => $id));
    }

    public function findByItem(Item $item, $asPaginator = false)
    {
	$qb = $this
	  ->createQueryBuilder('n')
	  ->add('where', 'n.item = ?1')
	  ->setParameter(1, $item->getId())
	  ->orderBy('n.created_at', 'DESC');

        if ($asPaginator) {
            return new Pagerfanta(new DoctrineORMAdapter($qb->getQuery()));
        }

        return $qb->getQuery()->execute();
    }

    public function findLatest($limit = 0)
    {
	$qb = $this
	  ->createQueryBuilder('n')
	  ->orderBy('n.created_at', 'DESC')
	  ->setMaxResults($limit);

        return $qb->getQuery()->execute();
    }
}
