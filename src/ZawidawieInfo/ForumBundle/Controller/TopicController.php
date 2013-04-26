<?php

namespace ZawidawieInfo\ForumBundle\Controller;

use Herzult\Bundle\ForumBundle\Controller\TopicController as BaseTopicController;
use Herzult\Bundle\ForumBundle\Form\TopicForm;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Herzult\Bundle\ForumBundle\Model\Topic;
use Herzult\Bundle\ForumBundle\Model\Category;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Form\Error;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use ZawidawieInfo\ForumBundle\Form\NewTopicFormType;

class TopicController extends BaseTopicController
{
    public function newAction(Category $category = null)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $post = $this->get('herzult_forum.repository.post')->createNewPost();
        $this->get('forum.authorname_persistence')->loadPost($post);
        $topic = $this->get('herzult_forum.repository.topic')->createNewTopic();
        $topic->setFirstPost($post);
        if ($category) {
            $topic->setCategory($category);
        }
        $form = $this->createForm($this->get('lichess_forum.form_type.new_topic'), $topic);

        return $this->get('templating')->renderResponse('HerzultForumBundle:Topic:new.html.'.$this->getRenderer(), array(
            'form'      => $form->createView(),
            'category'  => $category
        ));
    }

    public function createAction(Category $category = null)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $topic = $this->get('herzult_forum.repository.topic')->createNewTopic();
        $topic->setCategory($category);
        $form = $this->createForm($this->get('lichess_forum.form_type.new_topic'), $topic);
        $form->bindRequest($this->get('request'));

        if(!$form->isValid()) {
            return $this->invalidCreate($category, $form);
        }

        $this->get('herzult_forum.blamer.topic')->blame($topic);
        $this->get('herzult_forum.blamer.post')->blame($topic->getFirstPost());

//         if ($this->get('herzult_forum.akismet')->isTopicSpam($topic)) {
//             $form['firstPost']->addError(new Error('Sorry, but your topic looks like spam. If you think it is an error, send me an email.'));
//             $this->get('logger')->warn('HerzultForumBundle:topic spam block: '.$topic->getFirstPost()->getAuthorName().' - '.$topic->getSubject());
//             return $this->invalidCreate($category, $form);
//         }

        $this->get('herzult_forum.creator.topic')->create($topic);
        $this->get('herzult_forum.creator.post')->create($topic->getFirstPost());

        $objectManager = $this->get('herzult_forum.object_manager');
        $objectManager->persist($topic);
        $objectManager->persist($topic->getFirstPost());
        $objectManager->flush();

        $objectManager->flush();

        $this->get('session')->setFlash('herzult_forum_topic_create/success', true);
        $url = $this->get('herzult_forum.router.url_generator')->urlForTopic($topic);
        $response = new RedirectResponse($url);
        $this->get('forum.authorname_persistence')->persistTopic($topic, $response);

        return $response;
    }

    protected function invalidCreate(Category $category, $form)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        return $this->render('HerzultForumBundle:Topic:new.html.twig', array(
            'form'      => $form->createView(),
            'category'  => $category
        ));
    }

    /**
     * Compatibility layer with old topic urls
     */
    public function showCompatAction($categorySlug, $slug, $id)
    {
        $topic = $this->get('herzult_forum.repository.topic')->findOneById($id);
        if(!$topic) {
            throw new NotFoundHttpException(sprintf('The topic with id "%s" does not exist', $id));
        }
        return new RedirectResponse($this->generateUrl('herzult_forum_topic_show', array('categorySlug' => $categorySlug, 'slug' => $topic->getSlug())));
    }
}
