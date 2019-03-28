<?php
/**
 * Created by PhpStorm.
 * User: Stéphane
 * Date: 28/03/2019
 * Time: 12:11
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserTest extends WebTestCase
{
    private function getValidator()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        return $kernel->getContainer()->get('validator');
    }

    public function testRoleNewUser()
    {
        $task = new User();
        $this->assertContains('ROLE_USER', $task->getRoles());
    }


    public function testUserValidator()
    {
        /**
         * @var ConstraintViolationListInterface $violationList
         */
        $violationList = $this->getValidator()->validate(new User());

        $this->assertEquals(2, $violationList->count());

        $this->assertEquals('Vous devez saisir un nom d\'utilisateur.', $violationList[0]->getMessage());

        $this->assertEquals('Vous devez saisir une adresse email.', $violationList[1]->getMessage());
    }

    public function testEmailValidator()
    {
        $user = new User();
        $user->setEmail('email non valide');
        /**
         * @var ConstraintViolationListInterface $violationList
         */
        $violationList = $this->getValidator()->validate($user);

        $this->assertEquals('Le format de l\'adresse n\'est pas correcte.', $violationList[1]->getMessage());
    }
}
