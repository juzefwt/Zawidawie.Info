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

	$mainNews = $em
	  ->getRepository('ZawidawieInfoCoreBundle:News')
	  ->createQueryBuilder('n')
	  ->where('n.is_published = ?1')
	  ->orderBy('n.created_at', 'DESC')
	  ->setMaxResults(1)
	  ->setParameter(1, true)
	  ->getQuery()
	  ->getSingleResult();
	  
	$mainArticle = $em
	  ->getRepository('ZawidawieInfoCoreBundle:Article')
	  ->createQueryBuilder('a')
	  ->where('a.is_published = ?1')
	  ->orderBy('a.created_at', 'DESC')
	  ->setMaxResults(1)
	  ->setParameter(1, true)
	  ->getQuery()
	  ->getSingleResult();

	$latestNews = $em
	  ->getRepository('ZawidawieInfoCoreBundle:News')
	  ->createQueryBuilder('n')
	  ->where('n.is_published = ?1')
	  ->orderBy('n.created_at', 'DESC')
	  ->setParameter(1, true)
	  ->setFirstResult(1)
	  ->setMaxResults(3)
	  ->getQuery()
	  ->getResult();

	$shortNews = $em
	  ->getRepository('ZawidawieInfoCoreBundle:News')
	  ->createQueryBuilder('n')
	  ->orderBy('n.created_at', 'DESC')
	  ->where('n.is_short = ?1')
	  ->andWhere('n.expires_at > ?2')
	  ->setMaxResults(1)
	  ->setParameter(1, true)
	  ->setParameter(2, new \Datetime("now"))
	  ->getQuery()
	  ->getResult();

	$latestArticles = $em
	  ->getRepository('ZawidawieInfoCoreBundle:Article')
	  ->createQueryBuilder('a')
	  ->where('a.is_published = ?1')
	  ->orderBy('a.created_at', 'DESC')
	  ->setFirstResult(1)
	  ->setMaxResults(2)
	  ->setParameter(1, true)
	  ->getQuery()
	  ->getResult();

	$latestAds = $em
	  ->getRepository('ZawidawieInfoCoreBundle:Ad')
	  ->createQueryBuilder('ad')
	  ->orderBy('ad.created_at', 'DESC')
	  ->add('where', 'ad.expires_at > ?1')
	  ->setParameter(1, new \Datetime("now"))
	  ->setMaxResults(4)
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
	  ->setMaxResults(3)
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
		array('mainNews' => $mainNews, 'mainArticle' => $mainArticle, 'latestNews' => $latestNews, 'shortNews' => $shortNews, 'latestArticles' => $latestArticles,
		      'latestAds' => $latestAds, 'latestPhotos' => $latestPhotos,
		      'latestPosts' => $latestPosts, 'investments' => $investments, 'searchForm' => $searchForm->createView(), 'keywords' => $keywords));
    }

    public function dashboardAction()
    {
        return $this->render('ZawidawieInfoCoreBundle:Default:dashboard.html.twig');
    }
}
