<?php

namespace ZawidawieInfo\CoreBundle\Model\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use ZawidawieInfo\CoreBundle\Entity\CatalogQuery;

class CatalogItemRepository extends ObjectRepository
{
    public function findPk($id)
    {
        return $this->findOneBy(array('id' => $id));
    }

    public function findSlug($slug)
    {
        return $this->findOneBy(array('slug' => $slug));
    }

    public function findByCategory($category_id, $asPaginator = false)
    {
	$qb = $this
	  ->createQueryBuilder('n')
	  ->where('n.category = ?1')
	  ->setParameters(array(1 => $category_id));

        if ($asPaginator) {
            return new Pagerfanta(new DoctrineORMAdapter($qb->getQuery()));
        }

        return $qb->getQuery()->execute();
    }

    public function findByQuery(&$query, $asPaginator = false)
    {
	$query = str_replace(array('\'', '"', '*'), '', $query);

	$this->assureBackwardCompatibility($query);

	$this->storeQuery($query);
	$taintedQuery = "%".$query."%";
	$qb = $this
	  ->createQueryBuilder('n')
	  ->leftJoin('n.keywords', 'k')
	  ->where('n.name LIKE ?1')
	  ->orWhere('k.name LIKE ?2')
	  ->setParameters(array(1 => $taintedQuery, 2 => $taintedQuery));

        if ($asPaginator) {
            return new Pagerfanta(new DoctrineORMAdapter($qb->getQuery()));
        }

        return $qb->getQuery()->execute();
    }

    protected function assureBackwardCompatibility(&$query)
    {
	$old_categories = array(
	    'banki' => 'bank',
	    'urzedy-pocztowe' => 'poczta',
	    'przychodnie-i-osrodki-zdrowia' => 'przychodnia',
	    'osrodki-pomocy-spolecznej' => 'MOPS',
	    'szkoly-podstawowe' => 'szkola',
	    'straz-miejska' => 'straż',
	    'straz-pozarna' => 'straż',
	    'nauka-i-szkolnictwo' => 'szkoła',
	    'uslugi-porzadkowe' => 'sprzątanie',
	    'zwierzeta-weterynarze-pielegnacja' => 'weterynarz',
	    'apteki' => 'apteka',
	    'rady-osiedli' => 'rada',
	    'kursy-prawa-jazdy' => 'prawo jazdy',
	);

	$query = isset($old_categories[$query]) ? $old_categories[$query] : $query;
    }

    protected function storeQuery($query)
    {
        $storedQuery = $this
	    ->getEntityManager()
	    ->getRepository('ZawidawieInfoCoreBundle:CatalogQuery')
	    ->createQueryBuilder('n')
	    ->where('n.query = ?1')
	    ->setParameters(array(1 => $query))
	    ->setMaxResults(1)
            ->getQuery()
	    ->getOneOrNullResult();

	$em = $this->getEntityManager();

	if (!$storedQuery)
	{
	    $newQuery = new CatalogQuery();
	    $newQuery->setQuery($query);

	    $em->persist($newQuery);
	    $em->flush();
	}
	else
	{
	    $storedQuery->incrementScore();
	    $em->persist($storedQuery);
	    $em->flush();
	}
	
    }

    public function getPopularKeywords($limit = 20)
    {
        return $this
	    ->getEntityManager()
	    ->getRepository('ZawidawieInfoCoreBundle:CatalogKeyword')
	    ->createQueryBuilder('n')
	    ->orderBy('n.name', 'ASC')
	    ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
