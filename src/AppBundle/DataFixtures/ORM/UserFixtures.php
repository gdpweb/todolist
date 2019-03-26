<?php

/*
 * This file is part of the Symfony package.
 * (c) Stéphane BRIERE <stephanebriere@gdpweb.fr>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $password = $this->encoder->encodePassword($user, 'todolist');
        $user->setPassword($password);
        $user->setEmail('admin@gdpweb.fr');
        $user->setRoles(['ROLE_USER,ROLE_ADMIN']);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('anonyme');
        $password = $this->encoder->encodePassword($user, 'todolist');
        $user->setPassword($password);
        $user->setEmail('anonyme@gdpweb.fr');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        for ($i = 1; $i < 5; ++$i) {
            $user = new User();
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
