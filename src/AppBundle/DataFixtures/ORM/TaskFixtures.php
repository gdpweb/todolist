<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TaskFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i < 5; ++$i) {
            $task = new Task();
            $task->getTitle('Tâche n°'.$i);
            $task->getContent('Ceci est la tâche n°'.$i);
            $task->
            $user->setUsername('user'.$i);
            $password = $this->encoder->encodePassword($user, 'todolist');
            $user->setPassword($password);
            $user->setEmail('user'.$i.'@gdpweb.fr');
            $user->setRoles(['ROLE_USER']);
            $this->addReference('user'.$i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
