<?php
/**
 * Created by PhpStorm.
 * User: Stéphane
 * Date: 26/03/2019
 * Time: 14:06
 */

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @return User[] Returns an array of Users objects
     */
    public function findByStatus()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.username')
            ->getQuery()->getResult();
    }
}
