<?php

namespace ZawidawieInfo\CoreBundle\Model\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use ZawidawieInfo\CoreBundle\Entity\Item;

class ItemRepository extends ObjectRepository
{
    public function findPk($id)
    {
        return $this->findOneBy(array('id' => $id));
    }

    public function findAll($asPaginator = false)
    {
	$qb = $this
	  ->createQueryBuilder('n')
	  ->orderBy('n.created_at', 'DESC');

        if ($asPaginator) {
            return new Pagerfanta(new DoctrineORMAdapter($qb->getQuery()));
        }

        return $qb->getQuery()->execute();
    }

    public function findAllInvestments($asPaginator = false)
    {
	$qb = $this
	  ->createQueryBuilder('n')
	  ->where('n.type = ?1')
	  ->setParameter(1, Item::TYPE_INVESTMENT)
	  ->orderBy('n.created_at', 'DESC');

        if ($asPaginator) {
            return new Pagerfanta(new DoctrineORMAdapter($qb->getQuery()));
        }

        return $qb->getQuery()->execute();
    }

    public function findAllDistricts($asPaginator = false)
    {
	$qb = $this
	  ->createQueryBuilder('n')
	  ->where('n.type = ?1')
	  ->setParameter(1, Item::TYPE_DISTRICT)
	  ->orderBy('n.created_at', 'DESC');

        if ($asPaginator) {
            return new Pagerfanta(new DoctrineORMAdapter($qb->getQuery()));
        }

        return $qb->getQuery()->execute();
    }

    public function findAllEvents($asPaginator = false)
    {
	$qb = $this
	  ->createQueryBuilder('n')
	  ->where('n.type = ?1')
	  ->setParameter(1, Item::TYPE_EVENT)
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
