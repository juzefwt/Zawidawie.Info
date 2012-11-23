<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ZawidawieInfo\CoreBundle\Entity\ItemAttachment;
use ZawidawieInfo\CoreBundle\Form\ItemAttachmentType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ItemAttachmentController extends Controller
{

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

	$attachment = new ItemAttachment();

	return $this->renderForm($attachment, 'item_attach_new');
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
	$form = $this->createForm(new ItemAttachmentType(), $item);

	if ($this->getRequest()->getMethod() === 'POST')
	{
	    $form->bindRequest($this->getRequest());
	    if ($form->isValid())
	    {
		$item->setUser($this->get('security.context')->getToken()->getUser());

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($item);
		$em->flush();

		return $this->redirect($this->generateUrl('item_show', array('slug' => $item->getItem()->getSlug())));
	    }
	}

        return $this->render('ZawidawieInfoCoreBundle:ItemAttachment:new.html.twig', array(
            'form'   => $form->createView(),
            'action' => $action,
            'item'   => $item,
        ));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

	$repo = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:ItemAttachment');
	$em = $this->getDoctrine()->getEntityManager();
        
        $file = $repo->findBy(array('id' => $id));
        $item = $file->getItem();
	$em->remove($file);
	$em->flush();

        $this->get('session')->setFlash('notice', 'Załącznik został usunięty.');

        return new RedirectResponse($this->get('router')->generate('item_show', array('slug' => $item->getItem()->getSlug())));
    }
}
