<?php

namespace ZawidawieInfo\CoreBundle\Tests\Acceptance;

use ZawidawieInfo\CoreBundle\Tests\Acceptance\AbstractAcceptanceTest;

class AddArticleAcceptanceTest extends AbstractAcceptanceTest
{
    public function testPostCreationPageShowsAForm()
    {
        $client = self::createClient();
        $crawler = $this->requestPostCreationPage($client);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('#article_form')->count());
        $this->assertEquals('Zapisz', $crawler->filter('#article_form .submit')->attr('value'));
    }

    public static function createClient(array $options = array(), array $server = array())
    {
        $client = static::createPersistentClient();
        static::authenticate($client);

        return $client;
    }

    public function testSubmitEmptyForm()
    {
        $client = self::createClient();
        $crawler = $this->requestPostCreationPage($client);
        $form = $crawler->selectButton('Zapisz')->form();
        $client->submit($form);
        $this->assertFalse($client->getResponse()->isRedirect());
        $this->assertEquals(1, $crawler->filter('#article_form')->count());
    }

    public function testSubmitValidForm()
    {
	$title = 'What the heck - '.uniqid();
        $message = 'Lorem ipsum dolor sit amet '.uniqid();
        $client = $this->createPersistentClient();
        $crawler = $this->requestPostCreationPage($client);
        $form = $crawler->selectButton('Zapisz')->form();
        $form['ArticleType[title]'] = $title;
        $form['ArticleType[content]'] = $message;
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertRegexp('/'.$message.'/', $client->getResponse()->getContent());
    }

    protected function requestPostCreationPage($client)
    {
        $url = $this->generateUrl($client, 'article_new');

        return $client->request('GET', $url);
    }
}
