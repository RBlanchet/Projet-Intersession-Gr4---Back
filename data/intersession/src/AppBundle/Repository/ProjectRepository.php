<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function findProjectNeed()
    {
        return $this->getEntityManager()
        ->createQuery(
        'SELECT p.id , p.name, p.active FROM AppBundle:Project p ORDER BY p.id ASC'
        )
        ->getResult();
    }

    public function findAllProjectByIdUser($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p.id , p.name, p.active FROM AppBundle:Project p 
                      INNER JOIN users_projects UP ON p.id = UP.project_id
                      where UP.user_id = $id '
            )
            ->getResult();
    }
}

