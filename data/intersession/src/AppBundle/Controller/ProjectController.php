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
use FOS\RestBundle\View\View;

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

        $dateStart = $this->stringToDatetime($request->request->all()['date_start']);
        $dateEnd = $this->stringToDatetime($request->request->all()['date_end']);

        $validate = $this->checkDateValidate($dateStart, $dateEnd);

        if ($form->isValid() && !is_array($validate)) {
            $em = $this->get('doctrine.orm.entity_manager');
            $project->setCreatedAt(new \DateTime('now'));
            $project->setCreatedBy($this->getUser()->getId());
            $project->setDateStart($dateStart);
            $project->setDateEnd($dateEnd);
            $project->setActive(true);
            if (isset($request->request->all()['hour_pool'])) {
                $project->setHourPool($request->request->all()['hour_pool']);
            } else {
                $project->setHourPool(0);
            }
            $project->setHourSpend(0);
            $em->persist($project);
            $em->flush();
            return $project;
        } elseif (is_array($validate)) {
            return View::create($validate, 401);
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/projects/{id}")
     */
    public function removeProjectAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $project = $em->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        /* @var $project Project */

        if ($project) {
            $em->remove($project);
            $em->flush();

            return View::create(["message" => "Le projet à bien été supprimé."]);
        } else {
            return $this->projectNotFound();
        }
    }


    /**
     * @Rest\View(serializerGroups={"project"})
     * @Rest\Patch("/projects/{id}")
     */

    public function patchProjectAction(Request $request)
    {
        return $this->updateProject($request, false);
    }

    /**
     * @param Request $request
     * @param $clearMissing
     * @return Project|\FOS\RestBundle\View\View|\Symfony\Component\Form\FormInterface
     */
    private function updateProject(Request $request, $clearMissing)
    {
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        /* @var $project Project */

        if (empty($project)) {
            return $this->projectNotFound();
        }

        if ($clearMissing) {
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = [];
        }

        $form = $this->createForm(ProjectType::class, $project, $options);

        $form->submit($request->request->all(), $clearMissing);

        if (isset($request->request->all()['date_start'])) {
            $startAt = $this->stringToDatetime($request->request->all()['date_start']);
        } else {
            $startAt = true;
        }

        if (isset($request->request->all()['date_end'])) {
            $endAt = $this->stringToDatetime($request->request->all()['date_end']);
        } else {
            $endAt = true;
        }

        if ($form->isValid() && $startAt && $endAt) {
            $project->setCreatedAt(new \DateTime('now'));
            $project->setCreatedBy($this->getUser()->getId());
            if (is_object($startAt)) {
                $project->setDateStart($startAt);
            }
            if (is_object($endAt)) {
                $project->setDateEnd($endAt);
            }
            $em = $this->get('doctrine.orm.entity_manager');
            $em->merge($project);
            $em->flush();

            return $project;
        } elseif (!$startAt || !$endAt) {
            return View::create(["message" => "Le format des dates n'est pas compatible."], 500);
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Get("/project/{id}/{idUser}")
     */
    public function addUserAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $project = $em->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        /* @var $project Project */


        $user = $em->getRepository('AppBundle:User')
            ->find($request->get('idUser'));
        /* @var $user User */

        if ($project) {
            $project->addUser($user);
            $em->merge($project);
            $em->flush();

            return View::create(["message" => "Le projet à bien été supprimé."]);
        } else {
            return $this->projectNotFound();
        }
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    private function projectNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
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