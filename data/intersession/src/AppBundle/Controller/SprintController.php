<?php //src/AppBundle/Controller/SprintController

namespace AppBundle\Controller;

use AppBundle\Entity\Sprint;
use AppBundle\Entity\Project;
use AppBundle\Form\Type\SprintType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\FOSRestBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SprintController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"sprint"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/projects/{id}/sprints", name="sprint_creation")
     */
    public function postSprintsAction(Request $request)
    {
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        if($project){
            $sprint = new Sprint();
            $form = $this->createForm(SprintType::class, $sprint);

            $form->submit($request->request->all());

            $startAt = $this->stringToDatetime($request->request->all()['startAt']);
            $endAt = $this->stringToDatetime($request->request->all()['endAt']);
            if ($form->isValid()){
                $em = $this->get('doctrine.orm.entity_manager');
                $sprint->setCreatedAt(new \DateTime('now'));
                $sprint->setCreatedBy($this->getUser()->getId());
                $sprint->setProject($project);
                foreach ($sprint->getTasks() as $task){
                    $task->setSprint($sprint);
                    $em->persist($task);
                }
                $sprint->setDateStart($startAt);
                $sprint->setDateEnd($endAt);
                $sprint->setTimeSpend($endAt - $startAt);
                $em->persist($sprint);
                $em->flush();
                return $sprint;

            } elseif (!$startAt || !$endAt) {
                return \FOS\RestBundle\View\View::create(["message" => "Date Format not compatible"], Response::HTTP_BAD_REQUEST);
            } else {
                return $form;
            }
        }
        else {
            return \FOS\RestBundle\View\View::create(["message" => "Project Not Found"], Response::HTTP_NOT_FOUND);

        }
    }

    /**
     * @Rest\View(serializerGroups={"sprint"})
     * @Rest\Get("/projects/{id}/sprints")
     */
    public function getSprintsAction(Request $request)
    {
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        return $project->getMeetings();
    }


    /**
     * @Rest\View(serializerGroups={"sprint"})
     * @Rest\Get("/sprints/{id}", name="sprint_get_one")
     */
    public function getSprintAction(Request $request)
    {
        $sprint = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Sprint')
            ->find($request->get('id'));

        if(empty($sprint))
        {
            return \FOS\RestBundle\View\View::create(['message' => 'Sprint not found'], Response::HTTP_NOT_FOUND);

        }
        return $sprint;
    }

    /**
     * @Rest\View(serializerGroups={"sprint"})
     * @Rest\Patch("/sprints/{id}")
     */
    public function patchSprintAction(Request $request, Project $project){
        $sprint = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Sprint')
            ->find($request->get('id'));

        if (empty($sprint)){

            return \FOS\RestBundle\View\View::create(['message' => 'Sprint not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(SprintType::class, $sprint);

        $form->submit($request->all(), false);

        $startAt = $this->stringToDatetime($request->request->all()['startAt']);
        $endAt = $this->stringToDatetime($request->request->all()['endAt']);
        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $sprint->setCreatedAt(new \DateTime('now'));
            $sprint->setCreatedBy($this->getUser()->getId());
            $sprint->setProject($project);
            foreach ($sprint->getTasks() as $task){
                $task->setSprint($sprint);
                $em->persist($task);
            }
            $sprint->setDateStart($startAt);
            $sprint->setDateEnd($endAt);
            $sprint->setTimeSpend($endAt - $startAt);
            $em->persist($sprint);
            $em->flush();
            return $sprint;
        }
        else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"sprint"}, statusCode=Response::HTTP_NO_CONTENT)
     * @REST\Delete("/sprints/{id}")
     */
    public function removeSprintAction(Request $request){
        $em = $this->get('doctrine.orm.entity_manager');
        $sprint = $em->getRepository('AppBundle:Sprint')
            ->find($request->get('id'));
        if (!$sprint) {
            return;
        }
        if($sprint){
            $em->remove($sprint);
            $em->flush();
            return \FOS\RestBundle\View\View::create(['message' => 'Sprint deleted'], Response::HTTP_OK);
        }
    }
}