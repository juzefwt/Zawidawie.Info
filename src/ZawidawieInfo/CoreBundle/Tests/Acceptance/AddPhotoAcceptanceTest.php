<?php

namespace ZawidawieInfo\CoreBundle\Tests\Acceptance;

use ZawidawieInfo\CoreBundle\Tests\Acceptance\AbstractAcceptanceTest;

class AddPhotoAcceptanceTest extends AbstractAcceptanceTest
{
    public function testPostCreationPageShowsAForm()
    {
        $client = self::createClient();
        $crawler = $this->requestPostCreationPage($client);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('#photo_form')->count());
        $this->assertEquals('Zapisz', $crawler->filter('#photo_form .submit')->attr('value'));
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
        $this->assertEquals(1, $crawler->filter('#photo_form')->count());
    }

    public function testSubmitValidForm()
    {
	$title = 'What the heck - '.uniqid();
        $description = 'Lorem ipsum dolor sit amet '.uniqid();
        $client = $this->createPersistentClient();
        $crawler = $this->requestPostCreationPage($client);
        $form = $crawler->selectButton('Zapisz')->form();
        $form['PhotoType[title]'] = $title;
        $form['PhotoType[description]'] = $description;
        $form['PhotoType[file]'] = __DIR__.'/../../../../../web/apple-touch-icon.png';
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertRegexp('/'.$description.'/', $client->getResponse()->getContent());
    }

    protected function requestPostCreationPage($client)
    {
        $url = $this->generateUrl($client, 'photo_new');

        return $client->request('GET', $url);
    }
}
