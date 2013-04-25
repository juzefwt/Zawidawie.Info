<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ZawidawieInfo\CoreBundle\Entity\Item;
use ZawidawieInfo\CoreBundle\Form\CatalogSearchType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ZawidawieInfoCoreBundle:Default:index.html.twig');
    }

    public function homepageAction()
    {
	$em = $this->get('doctrine')
	  ->getEntityManager();

	$mainArticle = $em
	  ->getRepository('ZawidawieInfoCoreBundle:Article')
	  ->createQueryBuilder('n')
	  ->where('n.is_published = ?1')
	  ->orderBy('n.created_at', 'DESC')
	  ->setMaxResults(1)
	  ->setParameter(1, true)
	  ->getQuery()
	  ->getSingleResult();

	$latestArticles = $em
	  ->getRepository('ZawidawieInfoCoreBundle:Article')
	  ->createQueryBuilder('a')
	  ->where('a.is_published = ?1')
	  ->orderBy('a.created_at', 'DESC')
	  ->setFirstResult(1)
	  ->setMaxResults(5)
	  ->setParameter(1, true)
	  ->getQuery()
	  ->getResult();

	$latestPhotos = $em
	  ->getRepository('ZawidawieInfoCoreBundle:Photo')
	  ->createQueryBuilder('p')
	  ->orderBy('p.created_at', 'DESC')
	  ->setMaxResults(4)
	  ->getQuery()
	  ->getResult();

	$latestPosts = $em
	  ->getRepository('ZawidawieInfoForumBundle:ForumPost')
	  ->createQueryBuilder('p')
	  ->orderBy('p.id', 'DESC')
	  ->setMaxResults(4)
	  ->getQuery()
	  ->getResult();

	$investments = $em
	  ->getRepository('ZawidawieInfoCoreBundle:Photo')
	  ->createQueryBuilder('p')
	  ->innerJoin('p.item', 'it')
	  ->add('where', 'it.type = ?1')
	  ->setParameter(1, Item::TYPE_INVESTMENT)
	  ->groupBy('p.item')
	  ->orderBy('p.created_at', 'DESC')
	  ->setMaxResults(3)
	  ->getQuery()
	  ->getResult();

        $keywords = $em
            ->getRepository('ZawidawieInfoCoreBundle:CatalogItem')
            ->getPopularKeywords();

        $searchForm = $this->createForm(new CatalogSearchType(), null);

        return $this->render('ZawidawieInfoCoreBundle:Default:homepage.html.twig', 
		array('mainArticle' => $mainArticle, 'latestArticles' => $latestArticles, 'latestPhotos' => $latestPhotos,
		      'latestPosts' => $latestPosts, 'investments' => $investments, 'searchForm' => $searchForm->createView(), 'keywords' => $keywords));
    }

    public function dashboardAction()
    {
        return $this->render('ZawidawieInfoCoreBundle:Default:dashboard.html.twig');
    }
}
