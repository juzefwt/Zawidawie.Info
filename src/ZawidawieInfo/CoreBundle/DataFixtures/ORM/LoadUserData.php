<?php

namespace ZawidawieInfo\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use ZawidawieInfo\CoreBundle\Entity\CatalogCategory;
use ZawidawieInfo\CoreBundle\Entity\CatalogItem;
use ZawidawieInfo\CoreBundle\Entity\User;
use ZawidawieInfo\CoreBundle\Entity\News;
use ZawidawieInfo\CoreBundle\Entity\Article;
use ZawidawieInfo\CoreBundle\Entity\Photo;
use ZawidawieInfo\CoreBundle\Entity\Ad;
use ZawidawieInfo\CoreBundle\Entity\Item;
use ZawidawieInfo\ForumBundle\Entity\ForumCategory;
use ZawidawieInfo\ForumBundle\Entity\ForumTopic;
use ZawidawieInfo\ForumBundle\Entity\ForumPost;


class LoadUserData implements FixtureInterface
{
    public function load($manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('admin');
        $userAdmin->setEmail('juzefwt@wp.pl');
	$userAdmin->setEnabled(true);
        $userAdmin->setAlgorithm('sha1');

        $manager->persist($userAdmin);
        $manager->flush();

	for ($i = 0; $i < 10; $i++)
	{
	    $n = new News();
	    $n->setTitle("Lądowanie wojsk reptaliańskich w Marianowie");
	    $n->setContent("There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration 
	    in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use 
	    a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the 
	    Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true 
	    generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, 
	    to generate Lorem Ipsum which looks reasonable. 
	    The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.");
	    $n->setUser($userAdmin);
	    $n->setPath('placeholder.jpg');
	    $manager->persist($n);
	    $manager->flush();
	}

	for ($i = 0; $i < 10; $i++)
	{
	    $n = new Article();
	    $n->setTitle("Kotlet z kota w okresie buntu pralek");
	    $n->setContent("There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration 
	    in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use 
	    a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the 
	    Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true 
	    generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, 
	    to generate Lorem Ipsum which looks reasonable. 
	    The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.");
	    $n->setUser($userAdmin);
	    $manager->persist($n);
	    $manager->flush();
	}

	for ($i = 0; $i < 10; $i++)
	{
	    $n = new Photo();
	    $n->setTitle("Łysy jamnik w pokrzywach");
	    $n->setDescription('Lorem ipsum dolor sit amet.');
	    $n->setPath('placeholder.jpg');
	    $n->setUser($userAdmin);
	    $manager->persist($n);
	    $manager->flush();
	}

	$category = new ForumCategory();
	$category->setName("Inwestycje");
	$category->setDescription('Local news n stuffz');
	$manager->persist($category);
	$manager->flush();

	for ($i = 0; $i < 10; $i++)
	{
	    $t = new ForumTopic();
	    $t->setSubject("Autostradowa obwodnica Psiego Pola");
	    $t->setCategory($category);
	    $t->setPulledAt(new \DateTime());
	    $manager->persist($t);
	    $manager->flush();

	    for ($i = 0; $i < 10; $i++)
	    {
		$n = new ForumPost();
		$n->setAuthor($userAdmin);
		$n->setTopic($t);
		$n->setNumber($i);
		$n->setMessage("There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration 
	          in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use 
	          a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.");
		$manager->persist($n);
		$manager->flush();
	    }
	}

	for ($i = 0; $i < 5; $i++)
	{
	    for ($j = 0; $j < 20; $j++)
	    {
		$n = new CatalogItem();
		$n->setName("Firma #".$j);
		$n->setUser($userAdmin);
		$n->setDescription("There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration 
	          in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use 
	          a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.");
	        $n->setAddress("Marianów 15");
		$manager->persist($n);
		$manager->flush();
	    }
	}

	for ($j = 0; $j < 20; $j++)
	{
	    $n = new Ad();
	    $n->setTitle("Sprzedam ".$j."kg knura");
	    $n->setUser($userAdmin);
	    $n->setContent("There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration 
	      in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use 
	      a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.");
	    $n->setCreatedAt(new \DateTime("now"));
	    $n->setExpiresAt(new \DateTime('today +1 month'));
	    $manager->persist($n);
	    $manager->flush();
	}

        for ($j = 0; $j < 20; $j++)
        {
            $n = new Item();
            $n->setName("Jakis tam obiekt");
            $n->setUser($userAdmin);
            $n->setDescription("There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration
              in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use
              a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.");
            $n->setCreatedAt(new \DateTime('today -1 month'));
            $n->setUpdatedAt(new \DateTime("now"));
            $n->setType(Item::TYPE_DISTRICT);
            $manager->persist($n);
            $manager->flush();
        }
    }
}