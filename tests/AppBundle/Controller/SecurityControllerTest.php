<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url, $expected)
    {
        $this->client->request('GET', $url);
        $this->assertSame($expected, $this->client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        return [
            ['/login', 200],
        ];
    }

    public function testLoginAction()
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'todolist';
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirection());
    }

    public function testLogoutCheckAction()
    {
        $this->client->request('GET', '/logout');
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/'));
    }
}
