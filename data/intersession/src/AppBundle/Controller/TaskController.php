<?php // src/AppBundle/Controller/TaskController.php

namespace AppBundle\Controller;



use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\Type\TaskType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Controller\BaseController;


class TaskController extends BaseController {

    /**
     * @Rest\View(serializerGroups={"task"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/projects/{id}/tasks", name="task_creation")
     */
    public function postTasksAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->submit($request->request->all());

            $startAt = $this->stringToDatetime($request->request->all()['startAt']);
            $endAt = $this->stringToDatetime($request->request->all()['endAt']);
            if ($form->isValid())
            {
                $em = $this->get('doctrine.orm.entity_manager');
                foreach ($task->getUsers() as $user){
                    $user->setTasks($task);
                    $em->persist($user);
                }
                $task->setCreatedAt(new \DateTime('now'));
                $task->setCreatedBy($this->getUser()->getId());
                $task->setDateStart($startAt);
                $task->setDateEnd($endAt);
                $task->setTimeSpend($endAt - $startAt);
                $em->flush();
                return $task;

            }
        else {
            return $form;
        }
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("/tasks")
     */
    public function getTasksAction(Request $request)
    {
        $tasks = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Task')
            ->findAll();
        return $tasks;
    }
    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("/projects/{id}/tasks")
     */
    public function getTasksByProjectAction(Request $request)
    {
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get("id"));
        if ($project){
            return $project->getTasks();
        }
        else {
            return \FOS\RestBundle\View\View::create(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("/users/{id}/tasks")
     */
    public function getTasksByUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get("id"));
        if($user){
            return $user->getTasks();
        }
        else {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("/projects/{projectId}/users/{userId}/tasks")
     */
    public function getTasksPerUserAndProjectAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('userId'));

        if ($user) {
            $tasks = $user->getTasks();
            $attributedTasks = [];
            foreach( $tasks as $task){
                if ($task->getProject() == $request->get('projectId')){
                    $attributedTasks[] = $task;
                }
            }
            return $attributedTasks;
        }
        else {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
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
        /* @var $task Task */

        if (empty($task)){
            return \FOS\RestBundle\View\View::create(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }


        $form = $this->createForm(TaskType::class, $task);

        $form->submit($request->request->all(), false);

        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            foreach ($task->getUsers() as $user){
                $user->addTasks($task);
                $em->persist($user);
            }
            $users = $task->getUsers();
            if ($users){
                $count = count($users);
            }
            else{
                $count = 1;
            }

            $dateStart = $this->stringToDatetime($request->request->all()['dateStart']);
            $endAt = $this->stringToDatetime($request->request->all()['endAt']);
            if ($dateStart && $endAt){
                $task->setDateStart($dateStart);
                $task->setDateEnd($endAt);
                $task->setTimeSpend($dateStart, $endAt, $count);
            }


            $em->merge($task);
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
        if (!$task) {
            return;
        }
        $status = $task->getStatus;
        if($task){
            $em->remove($status);
            $em->remove($task);
            $em->flush();
        }
    }
}