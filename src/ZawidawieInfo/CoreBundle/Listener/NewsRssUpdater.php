<?php

namespace ZawidawieInfo\CoreBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Nekland\Bundle\FeedBundle\Factory\FeedFactory;
use ZawidawieInfo\CoreBundle\Entity\News;

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

        if ($entity instanceof News) {
	    try {
		$this->feed_factory->load('news', 'rss_file');
		$feed = $this->feed_factory->get('news');

		try {
		    $feed->replace($entity->getId(), $entity);
		} catch (\Exception $e) {
		    $feed->add($entity);
		}

		$this->feed_factory->render('news', 'rss');
	    } catch (\Exception $e) {
		//czasami tu był wysyp z powodu uciętych w pół znaków unicode'u
	    }
        }
    }
}