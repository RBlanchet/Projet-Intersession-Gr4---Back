<?php //src/AppBundle/Controller/RoleController.php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\Type\RoleType;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Entity\Project;

class RoleController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"roleByProject"})
     * @Rest\Get("/projects/{id}/roles")
     */
    public function getRolesAction(Request $request)
    {

        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Role')
            ->findBy(array('project'=>$request->get('id')));
        /* @var $roles Role[] */

        if (empty($project)) {
            return $this->projectNotFound();
        }
        return $project;
    }

    /**
     * @Rest\View(serializerGroups={"roleByUser"})
     * @Rest\Get("/users/{id}/roles")
     */
    public function getRolesPerUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));
/* @var $user User*/
        if (empty($user)) {
            return $this->userNotFound();
        }
        return $user->getProtectedRoles();
    }
    /**
     * @Rest\View(serializerGroups={"roleByProject"})
     * @Rest\Get("/projects/{projectId}/users/{userId}/roles")
     */
    public function getRolesPerUserAndProjectAction(Request $request)
    {
        $roles = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Role')
            ->findBy(array('project' => $request->get('projectId') ,'user' => $request->get('userId')));

        if (empty($roles)) {
            return $this->userNotFound();
        }
        return $roles;
    }

    /**
     * @Rest\View(serializerGroups={"role"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("users/{id}/roles")
     */
    public function postJobsUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));
        /* @var $user User */

        if (empty($user)) {
            return $this->userNotFound();
        }
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->request->all()['project']);
        /* @var $project Project */

        $role = new Role();


        $form = $this->createForm(RoleType::class, $role);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $role->setUser($user);
            $role->setProject($project);
            $user->addProtectedRole($role);
            $registered = false;
            foreach($project->getUsers() as $projectUser){
                if ($projectUser == $user){
                    $registered = true;
                }
            }
            if ($registered == false){
                $project->addUser($user);
            }
            $em->persist($user);
            $em->persist($project);
            $em->persist($role);
            $em->flush();
            return $role;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"role"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("projects/{id}/roles")
     */
    public function postJobsProjectAction(Request $request)
    {
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        /* @var $project Project */

        if (empty($project)) {
            return $this->projectNotFound();
        }
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->request->all()['user']);
        /* @var $user User */

        $role = new Role();


        $form = $this->createForm(RoleType::class, $role);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $role->setProject($project);
            $role->setUser($user);
            $user->addProtectedRole($role);
            $registered = false;
            foreach($project->getUsers() as $projectUser){
                if ($projectUser == $user){
                    $registered = true;
                }
            }
            if ($registered == false){
                $project->addUser($user);
            }
            $em->persist($user);
            $em->persist($project);
            $em->persist($role);
            $em->flush();
            return $role;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"role"})
     * @Rest\Patch("roles/{id}")
     */
    public function patchJobsAction(Request $request)
    {
        $role = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Role')
            ->find($request->get('id'));

        if (empty($role)) {
            return $this->roleNotFound();
        }

        $form = $this->createForm(RoleType::class, $role);

        $form->submit($request->request->all(), false);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($role);
            $em->flush();
            return $role;
        } else {
            return $form;
        }
    }



    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
    private function projectNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
    }
    private function roleNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Role not found'], Response::HTTP_NOT_FOUND);
    }
}