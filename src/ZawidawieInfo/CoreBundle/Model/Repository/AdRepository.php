<?php

namespace ZawidawieInfo\CoreBundle\Model\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

class AdRepository extends ObjectRepository
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

    public function findLatest($limit = 0)
    {
	$qb = $this
	  ->createQueryBuilder('n')
	  ->orderBy('n.created_at', 'DESC')
	  ->setMaxResults($limit);

        return $qb->getQuery()->execute();
    }
}
