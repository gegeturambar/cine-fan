<?php

namespace AppBundle\Repository;

/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getDefaultRole(){
        $name = 'ROLE_USER';
        $t= $this->createQueryBuilder("role")
            ->where('role.name LIKE :name ')
            ->setParameter('name',"ROLE_USER")
            ->getQuery()
            ->getSingleResult()
            ;
            return $t;
    }
}
