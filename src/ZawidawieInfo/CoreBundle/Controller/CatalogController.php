<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ZawidawieInfo\CoreBundle\Form\CatalogItemType;
use ZawidawieInfo\CoreBundle\Form\CatalogSearchType;
use ZawidawieInfo\CoreBundle\Entity\CatalogItem;
use ZawidawieInfo\CoreBundle\Entity\CatalogKeyword;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class CatalogController extends Controller
{
    public function indexAction()
    {
	$em = $this->get('doctrine')->getEntityManager();
        $form = $this->createForm(new CatalogSearchType(), null);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
		$data = $form->getData();
		$query = $data['query'];

		return $this->redirect($this->generateUrl('catalog_list', array('query' => $query)));
            }
        }

        $keywords = $em
            ->getRepository('ZawidawieInfoCoreBundle:CatalogItem')
            ->getPopularKeywords();

        return $this->render('ZawidawieInfoCoreBundle:Catalog:index.html.twig', array('keywords' => $keywords, 'searchForm' => $form->createView()));
    }

    public function listAction($query)
    {
	$em = $this->get('doctrine')->getEntityManager();
	$items = $em
	    ->getRepository('ZawidawieInfoCoreBundle:CatalogItem')
	    ->findByQuery($query, true);

        $page = $this->container->get('request')->query->get('page', 1);
        $items->setCurrentPage($page);
        $items->setMaxPerPage(10);

	return $this->render('ZawidawieInfoCoreBundle:Catalog:list.html.twig', array('query' => $query, 'items' => $items));
    }

    public function showAction($slug)
    {
        $item = $this->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:CatalogItem')
            ->findOneBySlug($slug);

        if (!$item)
            throw $this->createNotFoundException('Firma nie istnieje.');

        return $this->render('ZawidawieInfoCoreBundle:Catalog:show.html.twig', array('item' => $item));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $item = new CatalogItem();

        return $this->renderForm($item, 'catalog_new');
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $item = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:CatalogItem')->findPk($id);

        if (!$item)
            throw $this->createNotFoundException('Firma nie istnieje.');

        return $this->renderForm($item, 'catalog_edit');
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $repo = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:CatalogItem');
        $em = $this->getDoctrine()->getEntityManager();

        $item = $repo->findPk($id);

        if (!$item)
            throw $this->createNotFoundException('Firma nie istnieje.');

        $category = $item->getCategory();

        $em->remove($item);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Firma została usunięta.');

        return new RedirectResponse($this->get('router')->generate('catalog_category', array('slug' => $category->getSlug())));
    }

    protected function renderForm($item, $action)
    {
        $form = $this->createForm(new CatalogItemType($item), $item);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $item->setUser($this->get('security.context')->getToken()->getUser());
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($item);
                $em->flush();

		$message = \Swift_Message::newInstance()
		    ->setSubject('Nub dodaje firmę')
		    ->setFrom('juzefwt@wp.pl')
		    ->setTo('juzefwt@gmail.com')
		    ->setBody($item->getName().$item->getDescription())
		;
		$this->get('mailer')->send($message);

                return $this->redirect($this->generateUrl('catalog_show', array('slug' => $item->getSlug())));
            }
        }

        return $this->render('ZawidawieInfoCoreBundle:Catalog:new.html.twig', array(
            'form' => $form->createView(),
            'action' => $action,
            'item' => $item,
        ));
    }
}
