<?php

namespace ZawidawieInfo\CoreBundle\Tests\Acceptance;

require_once(__DIR__ . "/../../../../../app/AppKernel.php");

use ZawidawieInfo\CoreBundle\Tests\Acceptance\AbstractAcceptanceTest;

class AddNewsAcceptanceTest extends AbstractAcceptanceTest
{
    protected $_application;

    public function setUp()
    {
//         $kernel = new \AppKernel("test", true);
//         $kernel->boot();
//         $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
//         $this->_application->setAutoExit(false);
//         $this->runConsole("doctrine:schema:drop", array("--force" => true));
//         $this->runConsole("doctrine:schema:create");
//         $this->runConsole("cache:warmup");
//         $this->runConsole("doctrine:fixtures:load", array("--fixtures" => __DIR__ . "/../../DataFixtures"));
    }

    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

    public function testPostCreationPageShowsAForm()
    {
        $client = self::createClient();
        $crawler = $this->requestPostCreationPage($client);
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(1, $crawler->filter('#news_form')->count());
        $this->assertEquals('Zapisz', $crawler->filter('#news_form .submit')->attr('value'));
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
        $this->assertEquals(1, $crawler->filter('#news_form')->count());
    }

    public function testSubmitValidForm()
    {
	$title = 'What the heck - '.uniqid();
        $message = 'Lorem ipsum dolor sit amet '.uniqid();
        $client = $this->createPersistentClient();
        $crawler = $this->requestPostCreationPage($client);
        $form = $crawler->selectButton('Zapisz')->form();
        $form['NewsType[title]'] = $title;
        $form['NewsType[content]'] = $message;
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertRegexp('/'.$message.'/', $client->getResponse()->getContent());
    }

    protected function requestPostCreationPage($client)
    {
        $url = $this->generateUrl($client, 'news_new');

        return $client->request('GET', $url);
    }
}
