<?php // src/AppBundle/Controller/TaskController.php

namespace AppBundle\Controller;


use AppBundle\Entity\Task;
use AppBundle\Entity\Role;
use AppBundle\Entity\TaskStatus;
use AppBundle\Entity\User;
use AppBundle\Entity\Project;
use AppBundle\Form\Type\TaskType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Controller\BaseController;


class TaskController extends BaseController
{

    /**
     * @Rest\View(serializerGroups={"task"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/projects/{id}/tasks", name="task_creation")
     */
    public function postTasksAction(Request $request)
    {
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get("id"));

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $users = [];

        $data = $request->request->all();

        $form->submit($data);

        if (array_key_exists('start_at', $request->request->all())) {
            $startAt = $this->stringToDatetime($request->request->all()['start_at']);
        }
        if (array_key_exists('end_at', $request->request->all())) {
            $endAt = $this->stringToDatetime($request->request->all()['end_at']);
        }
        if (array_key_exists('cost', $request->request->all())) {
            $cost = $request->request->all()['cost'];
        }
        if (array_key_exists('users', $request->request->all())) {
            $users = $request->request->all()['users'];
        }

        if ($users) {
            $count = count($users);
        } else {
            $count = 1;
        }
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $status = $this->get('doctrine.orm.entity_manager')
                ->getRepository(TaskStatus::class)
                ->find($request->request->all()['status']);
            //$status = $task->getStatus();
            //$status->setTask($task);
            $em->persist($task);
            $task->setStatus($status);
            $task->setProject($project);
            $task->setCreatedAt(new \DateTime('now'));
            $task->setCreatedBy($this->getUser()->getId());
            $task->setActive(true);
            $task->setTimeSpend($this->timeSpend($startAt, $endAt));

            if (empty($cost)) {

            } else {
                $task->setCost($cost);
            }

            foreach ($users as $user) {
                $attributed = $this->get('doctrine.orm.entity_manager')
                    ->getRepository('AppBundle:User')
                    ->find($user);
                /* @var $attributed User */
                $attributed->addTask($task);
                $em->persist($attributed);
            }

            $em->persist($task);
            $em->flush();

            $project = $task->getProject();
            $this->updateProject($project);

            return $task;
        } else {
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
     * @Rest\View(serializerGroups={"project"})
     * @Rest\Get("/projects/{id}/tasks")
     */
    public function getTasksByProjectAction(Request $request)
    {
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get("id"));
        if ($project) {
            return $project->getTasks();
        } else {
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
        if ($user) {
            return $user->getTasks();
        } else {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Get("/users/{id}/tasks-status")
     */
    public function getTasksStatusByUserAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get("id"));

        $tasksStatus = $this->get('doctrine.orm.entity_manager')
            ->getRepository(TaskStatus::class)
            ->findAll();

        if ($user) {
            $data = array(
                'results' => array('Label', 'Value'),
            );

            foreach ($tasksStatus as $taskStatus) {
                $data[$taskStatus->getId()] = array($taskStatus->getTitle(), 0);
            }

            foreach ($user->getTasks() as $task) {
                $data[$task->getStatus()->getId()][1] = $data[$task->getStatus()->getId()][1] + 1;
            }

            return \FOS\RestBundle\View\View::create(['data' => $data], Response::HTTP_OK);
        } else {
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
            $all = $user->getTasks();
            $attributedTasks = [];
            foreach ($all as $task) {
                $filter = $this->getDoctrine()->getRepository(Task::class)->find($request->get('userId'));

                if ($filter->getProject() == $request->get('projectId')) {
                    $attributedTasks[] = $task;
                }
            }
            return $attributedTasks;
        } else {
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

        if (empty($task)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);

        }
        return $task;
    }

    /**
     * @Rest\View(serializerGroups={"task"})
     * @Rest\Patch("/tasks/{id}")
     */
    public function patchTaskAction(Request $request)
    {
        $task = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Task')
            ->find($request->get('id'));

        /* @var $task Task */

        if (empty($task)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }
        $startAt = "";
        $endAt = "";
        $cost = "";
        $users = "";
        $timeSpend = "";
        $form = $this->createForm(TaskType::class, $task);
        $form->submit($request->request->all(), false);
        if (array_key_exists('start_at', $request->request->all())) {
            $startAt = $this->stringToDatetime($request->request->all()['start_at']);
        }
        if (array_key_exists('end_at', $request->request->all())) {
            $endAt =$this->stringToDatetime($request->request->all()['end_at']);
        }
        if (array_key_exists('cost', $request->request->all())) {
            $cost = $request->request->all()['cost'];
        }
        if (array_key_exists('users', $request->request->all())) {
            $users = $request->request->all()['users'];
        }
        if (array_key_exists('time_spend', $request->request->all())) {
            $timeSpend = $request->request->all()['time_spend'];
        }
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            if (is_array($users)) {
                // On kick tous les user a la tache
                foreach ($task->getUsers() as $user) {
                    $task->removeUser($user);
                    $user->removeTask($task);
                    $em->persist($task);
                    $em->persist($user);
                }
                // On est obligé de flush afin que la base soit à jour
                $em->flush();
                // On rajoute les new user
                foreach ($users as $user) {
                    $newUser = $this->get('doctrine.orm.entity_manager')
                        ->getRepository('AppBundle:User')
                        ->find($user);
                    /* @var $newUser User */
                    $task->addUser($newUser);
                    $em->persist($task);
                    $em->flush();
                }
            }

            if ($users) {
                $count = count($users);
            } else {
                $count = 1;
            }
            if ($startAt) {
                $task->setStartAt($startAt);
            }
            if ($endAt) {
                $task->setEndAt($endAt);
            }

            if ($timeSpend == "" && ($startAt != "" || $endAt != "")) {
                $hours = $this->timeSpend($startAt, $endAt);

                $task->setTimeSpend($hours);
            }
            if ($cost == "" && $users) {
                $hours = $task->getTimeSpend();
                $price = 0;
                foreach ($users as $user) {
                    $role = $this->get('doctrine.orm.entity_manager')
                        ->getRepository('AppBundle:Role')
                        ->findOneBy(array('user' => $user, 'project' => $task->getProject()));
                    /* @var $role Role */
                    if (!empty($role)) {
                        $price += $role->getCost() * $hours;
                    }
                }
                $task->setCost($price);
            }
            $em->persist($task);
            $em->flush();
            $project = $task->getProject();
            $this->updateProject($project);

            return $task;
        } else {
            return $form;
        }
    }
    public function updateProject($id){
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($id);
        /* @var $project Project */
        $cost = 0;
        $duration = 0;
        foreach ($project->getTasks() as $task){
            $cost += $task->getCost();
            $duration += $task->getTimeSpend();
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $project->setCost($cost);
        $project->setHourSpend($duration);
        $em->persist($project);
        $em->flush();
        return $project;
    }

    /**
     * @Rest\View(serializerGroups={"task"}, statusCode=Response::HTTP_NO_CONTENT)
     * @REST\Delete("/tasks/{id}")
     */
    public function removeTaskAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $task = $em->getRepository('AppBundle:Task')
            ->find($request->get('id'));
        if (!$task) {
            return;
        }
        if ($task) {
            $task->setActive(false);
            $em->persist($task);
            $em->flush();
        }
    }
}