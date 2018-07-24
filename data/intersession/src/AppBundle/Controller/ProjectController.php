<?php

namespace AppBundle\Controller;

// Base Controller
use AppBundle\Controller\BaseController;

//Repository
use AppBundle\Entity\User;
use AppBundle\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

// Routing
use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations

// Request and Response
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use AppBundle\Form\Type\ProjectType;
/**
 * Class ProjectController
 * @package AppBundle\Controller
 */
class ProjectController extends BaseController
{
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"project"})
     * @Rest\Post("/projects")
     */
    public function postProjectAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project, ['validation_groups'=>['Default', 'New']]);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($project);
            $em->flush();
            return $project;
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"project"})
     * @Rest\Get("/projects/{id}")
     */
    public function getProjectAction(Request $request)
    {
        $projects = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        return $projects;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"projects"})
     * @Rest\Get("/projects")
     */
    public function getProjectsAction(Request $request)
    {
        $projects = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->findAll();

        return $projects;

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"project"})
     * @Rest\Get("/projects/users/{idUser}")
     */
    public function projectGetAllForUserAction($idUser)
    {
        $projects = $this->getDoctrine()
            ->getRepository(Project::class);
        return $projects->findAllProjectByIdUser($idUser);

    }
}