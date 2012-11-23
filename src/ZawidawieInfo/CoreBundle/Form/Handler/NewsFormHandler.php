<?php


namespace ZawidawieInfo\CoreBundle\Form\Handler;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use ZawidawieInfo\CoreBundle\Entity\News;

class NewsFormHandler
{
    protected $request;
    protected $form;
    protected $container;

    public function __construct(Form $form, Request $request, Container $container)
    {
        $this->form = $form;
        $this->request = $request;
        $this->container = $container;
    }

    public function process(News $news)
    {
        $news->setTagsString($this->container->get('doctrine')->getRepository('ZawidawieInfoCoreBundle:Tag')->getTagsStringForEntity($news));

        $this->form->setData($news);

        if ($this->request->getMethod() === 'POST')
        {
            $this->form->bindRequest($this->request);
            if ($this->form->isValid())
            {
                $this->onSuccess($news);
                return true;
            }
        }

        return false;
    }

    protected function onSuccess(News $news)
    {
       $tagManager = $this->container->get('zawidawie_core.tag_manager');

	$user = $this->container->get('security.context')->getToken()->getUser();
        $news->setUser($user);

	if (false == $user->hasRole('ROLE_SUPER_ADMIN'))
	{
	    $news->setIsPublished(false);
	    $news->setIsAccepted(false);

	    $message = \Swift_Message::newInstance()
		->setSubject('Nub dodaje artykuł')
		->setFrom('juzefwt@wp.pl')
		->setTo('juzefwt@gmail.com')
		->setBody($news->getTitle() . $news->getContent())
	    ;
	    $this->container->get('mailer')->send($message);
	}
	else
	{
	    $news->setIsPublished(true);
	    $news->setIsAccepted(true);
	}

        $tags = array();
        $arr = explode(",", $this->form->getData()->getTagsString());
        foreach ($arr as $k => $v)
        {
	    $tags[] = trim(strip_tags($v));
        }

	$tagManager->replaceTags($tagManager->loadOrCreateTags($tags), $news);

        $em = $this->container->get('doctrine')->getEntityManager();
        $em->persist($news);
        $em->flush();

	$tagManager->saveTagging($news);
    }

    public function getForm()
    {
        return $this->form;
    }
}
