<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ZawidawieInfo\CoreBundle\Entity\Item;
use ZawidawieInfo\CoreBundle\Form\ItemType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ItemController extends Controller
{
    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);
        $item = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Item')->findAll(true);
        $item->setCurrentPage($page);
        $item->setMaxPerPage(10);

        return $this->render('ZawidawieInfoCoreBundle:Item:index.html.twig', array('items' => $item, 'title' => 'Obiekty'));
    }

    public function investmentsAction()
    {
        $page = $this->get('request')->query->get('page', 1);
        $item = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Item')->findAllInvestments(true);
        $item->setCurrentPage($page);
        $item->setMaxPerPage(10);

        return $this->render('ZawidawieInfoCoreBundle:Item:index.html.twig', array('items' => $item, 'title' => 'Inwestycje'));
    }

    public function districtsAction()
    {
        $page = $this->get('request')->query->get('page', 1);
        $item = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Item')->findAllDistricts(true);
        $item->setCurrentPage($page);
        $item->setMaxPerPage(10);

        return $this->render('ZawidawieInfoCoreBundle:Item:index.html.twig', array('items' => $item, 'title' => 'Osiedla'));
    }

    public function eventsAction()
    {
        $page = $this->get('request')->query->get('page', 1);
        $item = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Item')->findAllEvents(true);
        $item->setMaxPerPage(10);
        $item->setCurrentPage($page);

        return $this->render('ZawidawieInfoCoreBundle:Item:index.html.twig', array('items' => $item, 'title' => 'Wydarzenia'));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $item = new Item();

        return $this->renderForm($item, 'item_new');
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $item = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Item')->findPk($id);

        if (!$item)
            throw $this->createNotFoundException('Obiekt nie istnieje.');

        return $this->renderForm($item, 'item_edit');
    }

    protected function renderForm($item, $action)
    {
        $form = $this->createForm(new ItemType(), $item);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $item->setUser($this->get('security.context')->getToken()->getUser());

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($item);
                $em->flush();

                return $this->redirect($this->generateUrl('item_show', array('slug' => $item->getSlug())));
            }
        }

        return $this->render('ZawidawieInfoCoreBundle:Item:new.html.twig', array(
            'form' => $form->createView(),
            'action' => $action,
            'item' => $item,
        ));
    }

    public function showAction($slug)
    {
        $item = $this->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:Item')
            ->findOneBySlug($slug);

        $subitems = $this->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:Item')
            ->findBy(array('parentItem' => $item->getId()));

        $articles = $this->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:Article')
            ->findBy(array('item' => $item->getId()));

        $photos = $this->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:Photo')
            ->findByItem($item, true);
        $page = $this->get('request')->query->get('page', 1);
        $photos->setMaxPerPage(9);
        $photos->setCurrentPage($page);

        $related = $this->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:Item')
            ->createQueryBuilder('q')
            ->select('i')
            ->from('ZawidawieInfo\CoreBundle\Entity\Item', 'i')
            ->where('i.type = :type')
            ->andWhere('i.id <> :id')
            ->setParameter('type', $item->getType())
            ->setParameter('id', $item->getId())
            ->getQuery()
            ->getResult();

        $attachments = $this->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:ItemAttachment')
            ->findBy(array('item' => $item->getId()));

        return $this->render('ZawidawieInfoCoreBundle:Item:show.html.twig', array(
            'item' => $item,
            'articles' => $articles,
            'subitems' => $subitems,
            'related' => $related,
            'attachments' => $attachments,
            'photos' => $photos));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $repo = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Item');
        $em = $this->getDoctrine()->getEntityManager();

        $item = $repo->findPk($id);
        $em->remove($item);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Item został usunięty.');

        return new RedirectResponse($this->get('router')->generate('item_index'));
    }
}
