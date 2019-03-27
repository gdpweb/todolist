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
    public function findByStatus($status = null)
    {
        $query = $this->createQueryBuilder('a');

        if ($status !== null) {
            $query->where('a.isDone=:status')
                ->setParameter('status', $status);
        }
        $query->orderBy('a.isDone', 'ASC');

        return $query->getQuery()->getResult();
    }
}
