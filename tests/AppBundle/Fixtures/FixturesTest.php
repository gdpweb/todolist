<?php

namespace Tests\AppBundle\Fixtures;

use AppBundle\DataFixtures\ORM\TaskFixtures;
use AppBundle\DataFixtures\ORM\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FixturesTest extends WebTestCase
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
     * @var ORMExecutor
     */
    private $executor;
    /**
     * @var Loader
     */
    private $loader;
    public function setUp()
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $encoder = $this->client->getContainer()->get('security.password_encoder');

        $this->loader = new Loader();
        $this->loader->addFixture(new UserFixtures($encoder));
        $this->loader->addFixture(new TaskFixtures());

        $purger = new ORMPurger($this->em);
        $this->executor = new ORMExecutor($this->em, $purger);
    }
    public function testExecuteFixtures()
    {
        $this->executor->execute($this->loader->getFixtures());
        $this->assertCount(2, $this->loader->getFixtures());
    }
}
