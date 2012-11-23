<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\DependencyInjection\ContainerAware;

use ZawidawieInfo\CoreBundle\Entity\Ad;
use ZawidawieInfo\CoreBundle\Form\AdType;


class AdController extends ContainerAware
{
    public function indexAction()
    {
        $page = $this->container->get('request')->query->get('page', 1);
        $ad = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Ad')->findAll(true);
        $ad->setCurrentPage($page);
        $ad->setMaxPerPage(10);

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:Ad:index.html.twig', array('ads' => $ad));
    }

    public function showAction($id)
    {
        $ad = $this->container->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:Ad')
            ->findPk($id);

        if (!$ad)
            throw new NotFoundHttpException('Ogłoszenie nie istnieje.');

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:Ad:show.html.twig', array('ad' => $ad));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $ad = new Ad();

        return $this->renderForm($ad, 'ads_new');
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id)
    {
        $ad = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Ad')->findPk($id);

        if (!$ad)
            throw new NotFoundHttpException('Ogłoszenie nie istnieje.');

        return $this->renderForm($ad, 'ads_edit');
    }

    protected function renderForm($ad, $action)
    {
        $form = $this->container->get('form.factory')->create(new AdType(), $ad);

        if ($this->container->get('request')->getMethod() === 'POST') {
            $form->bindRequest($this->container->get('request'));
            if ($form->isValid()) {
                $ad->setUser($this->container->get('security.context')->getToken()->getUser());
                $ad->setExpiresAt(new \DateTime('today +1 month'));
                $em = $this->container->get('doctrine')->getEntityManager();
                $em->persist($ad);
                $em->flush();

                $this->container->get('session')->setFlash('notice', 'Ogłoszenie zostało dodane.');
                return new RedirectResponse($this->container->get('router')->generate('ads_show', array('id' => $ad->getId())));
            }
        }

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:Ad:new.html.twig', array(
            'form' => $form->createView(),
            'action' => $action,
            'ad' => $ad,
        ));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction($id)
    {
        $repo = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Ad');
        $em = $this->container->get('doctrine')->getEntityManager();

        $ad = $repo->findPk($id);
        $em->remove($ad);
        $em->flush();

        $this->container->get('session')->setFlash('notice', 'Ogłoszenie zostało usunięte.');

        return new RedirectResponse($this->container->get('router')->generate('ad_index'));
    }
}
