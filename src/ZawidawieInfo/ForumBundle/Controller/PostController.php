<?php

namespace ZawidawieInfo\ForumBundle\Controller;

use Herzult\Bundle\ForumBundle\Controller\PostController as BasePostController;
use Herzult\Bundle\ForumBundle\Model\Topic;
use Herzult\Bundle\ForumBundle\Model\Post;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Form\FormError;
use ZawidawieInfo\ForumBundle\Form\PostFormType;

class PostController extends BasePostController
{
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $post = $this->get('herzult_forum.repository.post')->findOneById($id);

	if (!$post)
            throw $this->createNotFoundException('Post nie istnieje.');

	$form = $this->createForm(new PostFormType(), $post);

	if ($this->getRequest()->getMethod() === 'POST')
	{
	    $form->bindRequest($this->get('request'));

	    if ($form->isValid())
	    {
		$post = $form->getData();

		$objectManager = $this->get('herzult_forum.object_manager');
		$objectManager->persist($post);
		$objectManager->flush();

		$this->get('session')->setFlash('herzult_forum_post_create/success', true);
		$url = $this->get('herzult_forum.router.url_generator')->urlForPost($post);

		return new RedirectResponse($url);
	    }
	}
	else
	{
	    return $this->get('templating')->renderResponse('ZawidawieInfoForumBundle:Post:edit.html.'.$this->getRenderer(), array(
		'form'  => $form->createView(),
		'post'  => $post,
	    ));
	}
	
    }
}
