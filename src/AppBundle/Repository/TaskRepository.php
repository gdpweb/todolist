<?php
/**
 * Created by PhpStorm.
 * User: Stéphane
 * Date: 26/03/2019
 * Time: 14:06
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    public function findByStatus($status)
    {
        return $this->createQueryBuilder('a')
            ->where('a.isDone=:status')
            ->setParameter('status', $status)
            ->getQuery()->getResult();

    }
}