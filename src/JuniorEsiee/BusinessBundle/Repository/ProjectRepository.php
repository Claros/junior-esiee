<?php

namespace JuniorEsiee\BusinessBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Sonata\UserBundle\Entity\User;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends EntityRepository
{
    public function queryAll()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.depositDate');
    }

    public function queryUsers(User $user)
    {
        return $this->createQueryBuilder('p')
        	->leftJoin('p.students', 'stu')
        	->where('p.commercial = :user')
        	->orWhere('p.rbu = :user')
        	->orWhere('stu.id = :user')
        		->setParameter('user', $user->getId())
            ->orderBy('p.depositDate');
    }

    public function queryInProgress(User $user)
    {
        return $this->createQueryBuilder('p')
        	->leftJoin('p.students', 'stu')
        	// ->where('p.commercial != :user')
        	// ->andWhere('p.rbu != :user')
        	// ->andWhere('stu.id != :user')
        	// 	->setParameter('user', $user->getId())
        	->andWhere("p.state != 'state_aborted'")
        	->andWhere("p.state != 'state_closed'")
            ->orderBy('p.depositDate');
    }

    public function queryWaitingCommercial()
    {
        return $this->createQueryBuilder('p')
        	->where('p.commercialEnrollmentOpen = 1')
        	->andWhere("p.state = 'state_opened'")
            ->orderBy('p.depositDate');
    }

    public function queryWaitingStudents()
    {
        return $this->createQueryBuilder('p')
            ->where('p.studentsEnrollmentOpen = 1')
            ->andWhere("p.state = 'state_opened'")
            ->orderBy('p.depositDate');
    }

    public function queryMissingInfo()
    {
        return $this->createQueryBuilder('p')
        	->Where("p.state = 'state_waiting_information'")
            ->orderBy('p.depositDate');
    }

    public function queryAborted()
    {
        return $this->createQueryBuilder('p')
        	->Where("p.state = 'state_aborted'")
            ->orderBy('p.depositDate');
    }

    public function queryClosed()
    {
        return $this->createQueryBuilder('p')
        	->Where("p.state = 'state_closed'")
            ->orderBy('p.depositDate');
    }
}
