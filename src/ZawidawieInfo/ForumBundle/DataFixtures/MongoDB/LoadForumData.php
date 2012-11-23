<?php

namespace ZawidawieInfo\ForumBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use ZawidawieInfo\ForumBundle\Document\Category;
use ZawidawieInfo\ForumBundle\Document\Topic;
use ZawidawieInfo\ForumBundle\Document\Post;
use Bundle\ForumBundle\Creator\TopicCreator;
use Bundle\ForumBundle\Creator\PostCreator;

class LoadForumData implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
{
    protected $container;

    public function getOrder()
    {
        return 2;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load($manager)
    {
        $this->addCateg($manager, 'General Chess Discussion', 'The place to discuss general Chess topics');
        $this->addCateg($manager, 'Getting Started', 'What are the rules, how the pieces move? What are special moves like en passant? How do you draw or mate?');
        $this->addCateg($manager, 'Game analysis', 'Show us your game an let the community analyse it');
        $this->addCateg($manager, 'ZawidawieInfo Feedback', 'Bug reports, feature requests, suggestions... Help us making lichess.org better!');
        $topic = $this->addCateg($manager, 'Off-Topic Discussion', 'Everything that isn\'t related to chess');

        $user1 = $this->container->get('fos_user.repository.user')->findOneByUsernameCanonical('user1');
        $post = new Post();
        $post->setMessage('Test user message');
        $post->setAuthor($user1);
        $post->setTopic($topic);
        $this->container->get('herzult_forum.creator.post')->create($post);
        $manager->persist($post);

        $this->addBigTopic($manager, $topic->getCategory());

        $manager->flush(array('safe' => true));
    }

    protected function addBigTopic($manager, $category)
    {
        $topic = new Topic();
        $topic->setSubject('Big topic');
        $topic->setCategory($category);
        $this->container->get('herzult_forum.creator.topic')->create($topic);
        $manager->persist($topic);

        for ($it=1; $it<=25; $it++) {
            $post = new Post();
            $post->setMessage('big topic post '.$it);
            $post->setAuthorName('lichess.org staff');
            $post->setTopic($topic);
            $this->container->get('herzult_forum.creator.post')->create($post);
            $manager->persist($post);
        }
    }

    protected function addCateg($manager, $name, $description)
    {
        $categ = new Category();
        $categ->setName($name);
        $categ->setDescription($description);

        $topic = new Topic();
        $topic->setSubject('New forum category: '.$name);
        $topic->setCategory($categ);
        $this->container->get('herzult_forum.creator.topic')->create($topic);

        $post = new Post();
        $post->setMessage($description);
        $post->setAuthorName('lichess.org staff');
        $post->setTopic($topic);
        $this->container->get('herzult_forum.creator.post')->create($post);

        $manager->persist($categ);
        $manager->persist($topic);
        $manager->persist($post);

        return $topic;
    }
}
