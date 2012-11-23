<?php

namespace ZawidawieInfo\CoreBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

use ZawidawieInfo\CoreBundle\Entity\News;
use ZawidawieInfo\CoreBundle\Form\NewsType;

use ZawidawieInfo\CoreBundle\Form\Handler\NewsFormHandler;

class NewsController extends ContainerAware
{
    public function indexAction()
    {
        $page = $this->container->get('request')->query->get('page', 1);
        $news = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:News')->findAll(true);
        $news->setCurrentPage($page);
        $news->setMaxPerPage(10);

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:News:index.html.twig', array('news' => $news));
    }

    public function indexTagAction($tag)
    {
        $page = $this->container->get('request')->query->get('page', 1);
        $news = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Tag')->getEntitiesTaggedWith(new News(), $tag);
        $news->setCurrentPage($page);
        $news->setMaxPerPage(10);

        $tagObject = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Tag')->findOneBySlug($tag);

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:News:index.html.twig', array('news' => $news, 'tag' => $tagObject));
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function newAction()
    {
        $news = new News();
        return $this->handleObject($news);
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function editAction($id)
    {
        $news = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:News')->findPk($id);

        if (!$news)
            throw new NotFoundHttpException('News nie istnieje.');

        return $this->handleObject($news);
    }

    protected function handleObject($news)
    {
        $form = $this->container->get('zawidawie_core.news.form');
        $formHandler = $this->container->get('zawidawie_core.news.form.handler');

        if ($formHandler->process($news)) {
            return new RedirectResponse($this->container->get('router')->generate('news_show', array('slug' => $news->getSlug())));
        }

        $action = $news->getId() == false
            ? 'news_new'
            : 'news_edit';
        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:News:new.html.twig', array(
            'form' => $form->createView(),
            'action' => $action,
            'news' => $news,
        ));
    }

    public function showAction($slug)
    {
        $news = $this->container->get('doctrine')
            ->getRepository('ZawidawieInfoCoreBundle:News')
            ->findOneBySlug($slug);

        if (!$news)
            throw new NotFoundHttpException('News nie istnieje.');

        $news->incrementNbViews();

        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($news);
        $em->flush();

        $tags = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Tag')->getTagsForEntity($news);

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:News:show.html.twig', array('news' => $news, 'tags' => $tags));
    }

    /**
     * @Secure(roles="ROLE_SUPER_ADMIN")
     */
    public function deleteAction($id)
    {
        $repo = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:News');
        $em = $this->container->get('doctrine')->getEntityManager();

        $news = $repo->findPk($id);
        $em->remove($news);
        $em->flush();

        $this->container->get('session')->setFlash('notice', 'News został usunięty.');

        return new RedirectResponse($this->container->get('router')->generate('news_index'));
    }

    public function latestAction($news_to_ommit = null)
    {
        $news = $this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:News')->findLatest($news_to_ommit, 5);

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:News:latest.html.twig', array('news' => $news, 'title' => 'Inne aktualności'));
    }

    public function uploadAction()
    {
        $fileName = time() . "_" . $_FILES['upload']['name'];
        $url = __DIR__ . '/../../../../web/uploads/ckeditor/' . $fileName;

        if (($_FILES['upload'] == "none") OR (empty($_FILES['upload']['name']))) {
            $message = "Nic ciekawego.";
        }
        else if ($_FILES['upload']["size"] == 0) {
            $message = "Bo to zły plik był.";
        }
        else if (($_FILES['upload']["type"] != "image/pjpeg") AND ($_FILES['upload']["type"] != "image/jpeg") AND ($_FILES['upload']["type"] != "image/png")) {
            $message = "Przyjmujemy tylko obrazki (JPG/PNG).";
        }
        else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {
            $message = "Wygląda na to, że chakierujesz nam serwer. Nieładnie. Oczekuj nas.";
        }
        else
        {
            $message = "";
            move_uploaded_file($_FILES['upload']['tmp_name'], $url);
        }

        $url = 'http://' . $_SERVER['SERVER_NAME'] . '/app_dev.php/imagine/thumbnail/uploads/ckeditor/' . $fileName;
        $funcNum = $_GET['CKEditorFuncNum'];

        return $this->container->get('templating')->renderResponse('ZawidawieInfoCoreBundle:News:upload.html.twig', array('message' => $message, 'fileName' => $fileName, 'url' => $url, 'funcNum' => $funcNum));
    }
}
