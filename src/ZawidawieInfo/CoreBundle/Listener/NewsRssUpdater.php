<?php

namespace ZawidawieInfo\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Nekland\Bundle\FeedBundle\Factory\FeedFactory;
use ZawidawieInfo\CoreBundle\Entity\Article;

class NewsRssUpdater
{
    protected $feed_factory;

    public function __construct(FeedFactory $factory)
    {
        $this->feed_factory = $factory;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
	$this->updateRss($args);
    }

    public function updateRss(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof Article) {
	    try {
		$this->feed_factory->load('article', 'rss_file');
		$feed = $this->feed_factory->get('article');

		try {
		    $feed->replace($entity->getId(), $entity);
		} catch (\Exception $e) {
		    $feed->add($entity);
		}

		$this->feed_factory->render('article', 'rss');
	    } catch (\Exception $e) {
		//czasami tu był wysyp z powodu uciętych w pół znaków unicode'u
	    }
        }
    }
}