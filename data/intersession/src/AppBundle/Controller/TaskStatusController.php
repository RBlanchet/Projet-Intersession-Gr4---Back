<?php // src/AppBundle/Controller/TaskStatusController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\Type\TaskStatusType;
use AppBundle\Entity\TaskStatus;

class TaskStatusController extends Controller {

    /**
     * @Rest\View(serializerGroups={"taskStatus"})
     * @Rest\Get("/task-status")
     */
    public function getTaskListStatusAction()
    {
        $taskStatus = $this->get('doctrine.orm.entity_manager')
            ->getRepository(TaskStatus::class)
            ->findAll();

        return $taskStatus;
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("/tasks/{id}/status")
     */
    public function getTaskStatusAction(Request $request){
        $task = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Task')
            ->find($request->get('id'));

        if (empty($task)){
            return $this->taskNotFound();
        }
        return $task->getStatus();
    }

    /**
     * @Rest\View(serializerGroups={"task"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/tasks/{id}/status")
     */
    public function postTaskStatusAction(Request $request){
        $task = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Task')
            ->find($request->get('id'));

        if(empty($task)){
            return $this->taskNotFound();
        }

        $taskStatus = new TaskStatus();
        $taskStatus->setTask($task);
        $form = $this->createForm(TaskStatusType::class, $taskStatus);

        $form->submit($request->request->all());

        if ($form->isValid()){
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($taskStatus);
            $em->flush();
            return $taskStatus;
        }
        else{
            return $form;
        }
    }

    private function taskNotFound(){
        return \FOS\RestBundle\View\View::create(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
    }
}