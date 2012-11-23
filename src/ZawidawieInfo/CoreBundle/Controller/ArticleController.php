<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ZawidawieInfo\CoreBundle\Entity\Article;
use ZawidawieInfo\CoreBundle\Form\ArticleType;


class ArticleController extends Controller
{
    public function indexAction()
    {
        $page = $this->get('request')->query->get('page', 1);
        $article = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Article')->findAll(true);
        $article->setCurrentPage($page);
        $article->setMaxPerPage(10);

        return $this->render('ZawidawieInfoCoreBundle:Article:index.html.twig', array('article' => $article));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $article = new Article();

        return $this->renderForm($article, 'article_new');
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $article = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Article')->findPk($id);

        if (!$article)
            throw $this->createNotFoundException('Artykuł nie istnieje.');

        return $this->renderForm($article, 'article_edit');
    }

    protected function renderForm($article, $action)
    {
        $form = $this->createForm(new ArticleType(), $article);

        if ($this->getRequest()->getMethod() === 'POST')
        {
            $form->bindRequest($this->getRequest());
            if ($form->isValid())
            {
		$user = $this->get('security.context')->getToken()->getUser();
                $article->setUser($user);

		if (false == $user->hasRole('ROLE_SUPER_ADMIN'))
		{
		    $article->setIsPublished(false);

		    $message = \Swift_Message::newInstance()
			->setSubject('Nub dodaje artykuł')
			->setFrom('juzefwt@wp.pl')
			->setTo('juzefwt@gmail.com')
			->setBody($article->getTitle() . $article->getContent())
		    ;
		    $this->get('mailer')->send($message);
		}
		else
		{
		    $article->setIsPublished(true);
		    $article->setIsAccepted(true);
		}

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($article);
                $em->flush();

                return $this->redirect($this->generateUrl('article_show', array('slug' => $article->getSlug())));
            }
        }

        return $this->render('ZawidawieInfoCoreBundle:Article:new.html.twig', array(
            'form'   => $form->createView(),
            'action' => $action,
            'article'   => $article,
        ));
    }

    public function showAction($slug)
    {
        $article = $this->get('doctrine')
          ->getRepository('ZawidawieInfoCoreBundle:Article')
          ->findOneBySlug($slug);

        return $this->render('ZawidawieInfoCoreBundle:Article:show.html.twig', array('article' => $article));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $repo = $this->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Article');
        $em = $this->getDoctrine()->getEntityManager();

        $article = $repo->findPk($id);
        $em->remove($article);
        $em->flush();

        $this->get('session')->setFlash('notice', 'Artykuł został usunięty.');

        return new RedirectResponse($this->get('router')->generate('article_index'));
    }

    public function latestAction($article_to_ommit = null)
    {
        $articles = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Article')->findLatest($article_to_ommit, 5);

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:Article:latest.html.twig', array('articles' => $articles, 'title' => 'Inne artykuły'));
    }
}
