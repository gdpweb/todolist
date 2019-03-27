<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    private $client = null;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var User
     */
    protected $user;

    private $task;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->task = 'Tâche test';
        $this->logIn();
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
            ['/tasks', 200],
            ['/tasks/done', 200],
        ];
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = $this->task;
        $form['task[content]'] = $this->task;
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testEditAction()
    {
        $taskManager = $this->em->getRepository('AppBundle:Task');
        /** @var Task $task */
        $task = $taskManager->findOneBy(['title' => $this->task]);
        $crawler = $this->client->request('GET', '/tasks/' . $task->getId() . '/edit');
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[content]'] = 'Tâche à réaliser';
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }
    public function testToggleTaskAction()
    {
        $taskManager = $this->em->getRepository('AppBundle:Task');
        /** @var Task $task */
        $task = $taskManager->findOneBy(['title' => $this->task]);
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->request('GET', '/tasks/' . $task->getId() . '/toggle');
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }
    public function testDeleteAction()
    {
        $taskManager = $this->em->getRepository('AppBundle:Task');
        /** @var Task $task */
        $task = $taskManager->findOneBy(['title' => $this->task]);
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $task = $taskManager->findOneBy(['title' => 'Tâche n°5']);
        $this->client->request('GET', '/tasks/' . $task->getId() . '/delete');
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }



    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');
        $firewall = 'main';
        $userManager = $this->em->getRepository('AppBundle:User');
        /** @var User $user */
        $user = $userManager->findOneBy(['username' => 'user1']);
        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewall, $user->getRoles());
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
