<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 5; ++$i) {
            $task = new Task();
            $task->setTitle('Tâche n°' . $i);
            $task->setContent('Ceci est la tâche n°' . $i);
            $user = $this->getReference('user' . mt_rand(1, 4));
            $task->setUser($user);
            $manager->persist($task);
        }
        $task = new Task();
        $task->setTitle('Tâche n°5');
        $task->setContent('Ceci est la tâche n°5');
        $manager->persist($task);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
