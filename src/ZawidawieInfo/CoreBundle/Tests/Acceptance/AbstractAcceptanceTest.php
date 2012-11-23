<?php

namespace ZawidawieInfo\CoreBundle\Tests\Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;

abstract class AbstractAcceptanceTest extends WebTestCase
{
    protected static function createPersistentClient($cookieName = 'test')
    {
        $client = parent::createClient();
        $client->getCookieJar()->set(new Cookie(session_name(), $cookieName));

        return $client;
    }

    protected function generateUrl(Client $client, $route, $parameters = array())
    {
        return $client->getContainer()->get('router')->generate($route, $parameters);
    }

    protected static function authenticate(Client $client, $username = 'admin', $password = 'admin')
    {
        $client->request('POST', '/login_check', array(
            '_username' => $username,
            '_password' => $password
        ));
    }
}
