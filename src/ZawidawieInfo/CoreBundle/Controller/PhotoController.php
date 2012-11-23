<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ZawidawieInfo\CoreBundle\Entity\Photo;
use FPN\TagBundle\Entity\Tag;
use ZawidawieInfo\CoreBundle\Form\PhotoType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class PhotoController extends Controller
{
    public function indexAction($tag)
    {
        $page = $this->get('request')->query->get('page', 1);
        $photos = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Tag')->getEntitiesTaggedWith(new Photo(), $tag);
        $photos->setCurrentPage($page);
        $photos->setMaxPerPage(10);

        $tagObject = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Tag')->findOneBySlug($tag);

        return $this->render('ZawidawieInfoCoreBundle:Photo:index.html.twig', array('photos' => $photos, 'tag' => $tagObject));
    }

    public function showAction($slug)
    {
        $em = $this->get('doctrine')
            ->getEntityManager();

        $photo = $this->getRepository('Photo')->findOneBySlug($slug);

        if (!$photo)
            throw $this->createNotFoundException('Zdjęcie nie istnieje.');

        $tags = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Tag')->getTagsForEntity($photo);

        return $this->render('ZawidawieInfoCoreBundle:Photo:show.html.twig', array('photo' => $photo, 'tags' => $tags));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $photo = new Photo();

        return $this->renderForm($photo, 'photo_new');
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $item = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Photo')->findOneById($id);

        if (!$item)
            throw $this->createNotFoundException('Zdjęcie nie istnieje.');

        return $this->renderForm($item, 'photo_edit');
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $repo = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Photo');
        $em = $this->getDoctrine()->getEntityManager();

        $item = $repo->findOneById($id);

        if (!$item)
            throw $this->createNotFoundException('Zdjęcie nie istnieje.');

        $em->remove($item);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Zdjęcie zostało usunięte.');

        return new RedirectResponse($this->get('router')->generate('homepage'));
    }

    protected function renderForm($item, $action)
    {
        $tagManager = $this->get('zawidawie_core.tag_manager');
        $item->setTagsString($this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Tag')->getTagsStringForEntity($item));

        $form = $this->createForm(new PhotoType(), $item);

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $item->setUser($this->get('security.context')->getToken()->getUser());

                $tagManager->replaceTags($tagManager->loadOrCreateTags(explode(",", $form->getData()->getTagsString())), $item);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($item);
                $em->flush();

                if ($item->getItem())
                {
                    $item->getItem()->setLatestPhoto($item);
                    $em->persist($item->getItem());
                    $em->flush();
                }

                $tagManager->saveTagging($item);

                $this->get('session')->setFlash('success', 'Zdjęcie zostało zapisane!');

                return $this->redirect($this->generateUrl('photo_show', array('slug' => $item->getSlug())));
            }
        }

        return $this->render('ZawidawieInfoCoreBundle:Photo:new.html.twig', array(
            'form' => $form->createView(),
            'action' => $action,
            'photo' => $item,
        ));
    }

    protected function getRepository($class)
    {
        return $this->get('doctrine')->getEntityManager()->getRepository('ZawidawieInfoCoreBundle:' . $class);
    }
}
