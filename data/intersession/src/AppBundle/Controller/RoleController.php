<?php //src/AppBundle/Controller/RoleController.php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\Type\RoleType;
use AppBundle\Entity\Role;

class RoleController extends Controller
{

    /**
     * @Rest\View(serializerGroups={"role"})
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
     * @Rest\View(serializerGroups={"role"})
     * @Rest\Get("/users/{id}/roles")
     */
    public function getRolesPerUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));

        if (empty($user)) {
            return $this->userNotFound();
        }
        return $user->getRoles();
    }
    /**
     * @Rest\View(serializerGroups={"role"})
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
    public function postJobsAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));

        if (empty($user)) {
            return $this->userNotFound();
        }

        $role = new Role();
        $role->setUser($user);

        $form = $this->createForm(RoleType::class, $role);

        $form->submit($request->request->all());

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
}