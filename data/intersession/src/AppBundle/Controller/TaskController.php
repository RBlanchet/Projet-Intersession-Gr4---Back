<?php // src/AppBundle/Controller/TaskController.php

namespace AppBundle\Controller;

use AppBundle\Controller\BaseController;

use AppBundle\Entity\Task;
use AppBundle\Form\Type\TaskType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations as Rest;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends BaseController {

    /**
     * @Rest\View(serializerGroups={"task"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/", name="task_creation")
     */
    public function postTasksAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->submit($request->request->all());
        if ($form->isValid()){
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($task);
            $em->flush();
            return $task;
        }
        else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"task"} )
     * @Rest\Get("/tasks", name="task_get_all")
     */
    public function getTasksAction()
    {
        $tasks = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Task')
            ->findAll();
        return $tasks;
    }


    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("/tasks/{id}", name="task_get_one")
     */
    public function getTaskAction(Request $request)
    {
        $task = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Task')
            ->find($request->get('id'));

        if(empty($task))
        {
            return \FOS\RestBundle\View\View::create(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);

        }
      return $task;
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Patch("/tasks/{id}")
     */
    public function patchTaskAction(Request $request){
        $task = $this->get('doctrine.orm.entity_manager')
        ->getRepository('AppBundle:Task')
        ->find($request->get('id'));

        if (empty($task)){

            return \FOS\RestBundle\View\View::create(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->submit($request->all(), false);

        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->flush();
            return $task;
        }
        else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"task"}, statusCode=Response::HTTP_NO_CONTENT)
     * @REST\Delete("/tasks/{id}")
     */
    public function removeTaskAction(Request $request){
        $em = $this->get('doctrine.orm.entity_manager');
        $task = $em->getRepository('AppBundle:Task')
            ->find($request->get('id'));
        if($task){
            $em->remove($task);
            $em->flush();
        }
    }
}