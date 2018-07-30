<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 24/07/2018
 * Time: 10:08
 */

// src/DataFixtures/AppFixtures.php
namespace AppBundle\Fixtures;

use AppBundle\Entity\Job;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Entity\Role;
use AppBundle\Entity\Sprint;
use AppBundle\Entity\Task;
use AppBundle\Entity\TaskStatus;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;


class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        // Job
        $jobsName = array(
            1 => "Admin",
            2 => "Chef de Projet",
            3 => "Lead Developpeur",
            4 => "Developpeur"
        );
        $prefix = "job_";
        $count = 1;
        $jobsArray = array();
        foreach ($jobsName as $jobName) {
            $job = new Job();
            $job->setName($jobName);
            $job->setCreatedAt(new \DateTime('now'));

            $manager->persist($job);
            ${$prefix . $count} = $job;
            $count++;

            array_push($jobsArray, $job);
        }
        $manager->flush();

        // User
        $users = array(
            1 => array(
                "firstname" => "Admin",
                "lastname"  => "Admin",
                "email"     => "admin@amagantt.com",
                "password"  => "12345",
                "job"       => $job_1
            ),
            2 => array(
                "firstname" => "LD",
                "lastname"  => "LD",
                "email"     => "ld@amagantt.com",
                "password"  => "12345",
                "job"       => $job_2
            ),
            3 => array(
                "firstname" => "CP",
                "lastname"  => "CP",
                "email"     => "cp@amagantt.com",
                "password"  => "12345",
                "job"       => $job_3
            ),
            4 => array(
                "firstname" => "Dev",
                "lastname"  => "Dev",
                "email"     => "dev@amagantt.com",
                "password"  => "12345",
                "job"       => $job_4
            ),
            5 => array(
                "firstname" => "Dev 2",
                "lastname"  => "Dev 2",
                "email"     => "dev-2@amagantt.com",
                "password"  => "12345",
                "job"       => $job_4
            ),
            6 => array(
                "firstname" => "Dev 3",
                "lastname"  => "Dev 3",
                "email"     => "dev-3@amagantt.com",
                "password"  => "12345",
                "job"       => $job_4
            ),
        );
        $userArray = array();
        foreach ($users as $k => $v) {
            $user = new User();
            $user->setEmail($v['email']);
            $user->setLastname($v['lastname']);
            $user->setFirstname($v['firstname']);
            $user->setJob($v['job']);
            $user->setPassword('$2y$12$lHlUj31auOmaDuFHQU.EheSTAFM.S6GVrNJC8LHU5yKf2kIXpJ.Q.');
            $user->setActive(true);

            $manager->persist($user);

            array_push($userArray, $user);

            if ($k === 1) {
                $userProject = $user;
            }
        }

        $manager->flush();

        // Project

        $date = array(
            1 => array(
                'date_start'    => '2017-01-01 00:00:00',
                'date_end'      => '2018-01-01 00:00:00'
            ),
            2 => array(
                'date_start'    => '2016-01-01 00:00:00',
                'date_end'      => '2018-01-01 00:00:00'
            ),
            3 => array(
                'date_start'    => '2017-01-01 00:00:00',
                'date_end'      => '2017-06-01 00:00:00'
            ),
            4 => array(
                'date_start'    => '2017-01-01 00:00:00',
                'date_end'      => '2018-03-01 00:00:00'
            ),
            5 => array(
                'date_start'    => '2017-01-01 00:00:00',
                'date_end'      => '2018-12-01 00:00:00'
            )
        );

        $projectsArray = array();
        for ($i = 1; $i <= 5; $i++) {
            $project = new Project();
            $project->setName('Project' .  $i);
            $project->setDescription('Une description du projet numero ' . $i);
            $project->setPrice(rand(10000, 20000));
            $project->setCost(rand(10000, 20000));
            $project->setDateStart(new \DateTime($date[$i]['date_start']));
            $project->setDateEnd(new \DateTime($date[$i]['date_end']));
            $project->setHourPool(rand(1, 500));
            $project->setHourSpend(rand(1, 500));
            $project->setCreatedAt(new \DateTime('now'));
            $project->setCreatedBy($userProject->getId());
            $project->setActive(true);

            $manager->persist($project);

            array_push($projectsArray, $project);
        }

        $manager->flush();

        // Roles Project
        $rolesArray = array();
        for ($i = 1; $i <= 15; $i++) {
            $role = new Role();
            $role->setCost(rand(50, 500));
            $role->setProject($projectsArray[rand(0,count($projectsArray) - 1)]);
            $role->setJob($jobsArray[rand(0, count($jobsArray) - 1)]);
            $role->setUser($userArray[rand(0, count($userArray) - 1)]);

            $manager->persist($role);

            array_push($rolesArray, $role);
        }

        $manager->flush();

        // Sprint

        $date = array(
            1 => array(
                'date_start'    => '2017-12-01 00:00:00',
                'date_end'      => '2017-12-01 00:00:00'
            ),
            2 => array(
                'date_start'    => '2018-07-01 00:00:00',
                'date_end'      => '2017-07-15 00:00:00'
            ),
            3 => array(
                'date_start'    => '2017-01-01 00:00:00',
                'date_end'      => '2017-01-22 00:00:00'
            ),
            4 => array(
                'date_start'    => '2018-07-15 00:00:00',
                'date_end'      => '2018-07-26 00:00:00'
            ),
            5 => array(
                'date_start'    => '2018-09-22 00:00:00',
                'date_end'      => '2018-10-03 00:00:00'
            )
        );

        $sprintsArray = array();

        for ($i = 1; $i <= 5; $i++) {
            $sprint = new Sprint();
            $sprint->setProject($projectsArray[rand(0,count($projectsArray) - 1)]);
            $sprint->setCreatedBy($userArray[rand(0, count($userArray) - 1)]->getId());
            $sprint->setCreatedAt(new \DateTime('now'));
            $sprint->setActive(true);
            $sprint->setDateStart(new \DateTime($date[$i]['date_start']));
            $sprint->setDateEnd(new \DateTime($date[$i]['date_end']));
            $sprint->setName('Sprint ' . $i);

            $manager->persist($sprint);

            array_push($sprintsArray, $sprint);
        }

        $manager->flush();

        $tasks = array(
            1 => array(
                'parentId'      => null,
                'date_start'    => '2018-07-01 00:00:00',
                'date_end'      => '2018-08-01 00:00:00',
                'project'       => $projectsArray[1],
            ),
            2 => array(
                'parentId'      => 1,
                'date_start'    => '2018-07-16 00:00:00',
                'date_end'      => '2018-07-17 00:00:00',
                'project'       => $projectsArray[1],
            ),
            3 => array(
                'parentId'      => 1,
                'date_start'    => '2018-08-01 00:00:00',
                'date_end'      => '2018-08-01 15:00:00',
                'project'       => $projectsArray[1],
            ),
            4 => array(
                'parentId'      => 3,
                'date_start'    => '2018-08-01 06:00:00',
                'date_end'      => '2018-08-01 07:00:00',
                'project'       => $projectsArray[1],
            ),
            5 => array(
                'parentId'      => 3,
                'date_start'    => '2018-11-01 00:00:00',
                'date_end'      => '2018-12-01 00:00:00',
                'project'       => $projectsArray[1],
            ),
            6 => array(
                'parentId'      => null,
                'date_start'    => '2018-11-12 00:00:00',
                'date_end'      => '2018-11-23 00:00:00',
                'project'       => $projectsArray[2],
            ),
            7 => array(
                'parentId'      => null,
                'date_start'    => '2018-08-01 00:00:00',
                'date_end'      => '2018-10-22 00:00:00',
                'project'       => $projectsArray[2],
            ),
            8 => array(
                'parentId'      => 7,
                'date_start'    => '2018-09-10 00:00:00',
                'date_end'      => '2018-09-17 00:00:00',
                'project'       => $projectsArray[2],
            ),
            9 => array(
                'parentId'      => 8,
                'date_start'    => '2018-08-20 00:00:00',
                'date_end'      => '2018-08-25 00:00:00',
                'project'       => $projectsArray[2],
            ),
            10 => array(
                'parentId'      => 7,
                'date_start'    => '2018-10-15 00:00:00',
                'date_end'      => '2018-10-22 00:00:00',
                'project'       => $projectsArray[2],
            ),
            11 => array(
                'parentId'      => 9,
                'date_start'    => '2018-11-10 00:00:00',
                'date_end'      => '2018-11-12 00:00:00',
                'project'       => $projectsArray[2],
            ),
            12 => array(
                'parentId'      => 11,
                'date_start'    => '2018-10-05 00:00:00',
                'date_end'      => '2018-11-23 00:00:00',
                'project'       => $projectsArray[2],
            ),
            13 => array(
                'parentId'      => null,
                'date_start'    => '2018-12-25 00:00:00',
                'date_end'      => '2018-12-31 00:00:00',
                'project'       => $projectsArray[2],
            ),
            14 => array(
                'parentId'      => null,
                'date_start'    => '2018-12-15 00:00:00',
                'date_end'      => '2018-12-22 00:00:00',
                'project'       => $projectsArray[1],
            ),
            15 => array(
                'parentId'      => null,
                'date_start'    => '2018-12-22 00:00:00',
                'date_end'      => '2018-12-29 00:00:00',
                'project'       => $projectsArray[3],
            ),
        );

        $tasksArray = array();

        foreach ($tasks as $k => $v) {
            $task = new Task();
            $task->setProject($v['project']);
            $randSprint = rand(0, count($sprintsArray) - 1);
            if ($randSprint % 2 == 0) {
                $task->setSprint(null);
            } else {
                $task->setSprint($sprintsArray[$randSprint]);
            }
            if ($v['parentId']) {
                $task->setParent($v['parentId']);
            }
            $task->setName('Task ' . $k);
            $task->setDescription('Description de la tâche numero ' . $k);
            $task->setCost(rand(1, 500));
            $task->setStartAt(new \DateTime($v['date_start']));
            $task->setEndAt(new \DateTime($v['date_end']));
            $task->setCreatedAt(new \DateTime('now'));
            $task->setCreatedBy($userArray[rand(0, count($userArray) - 1)]->getId());
            $task->setTimeSpend(rand(20, 200));
            $task->setActive(true);
            $task->setStatus(rand(1,4));

            $manager->persist($task);

            array_push($tasksArray, $task);
        }

        $manager->flush();


        // Tasks Status

        $status = array(
            1 => 'En cours',
            2 => 'A faire',
            3 => 'Finit',
            4 => 'Validée'
        );

        foreach ($tasksArray as $k => $v) {
            $taskStatus = new TaskStatus();
            $taskStatus->setTask($v);
            $taskStatus->setTitle($status[rand(1, 4)]);

            $manager->persist($taskStatus);
        }

        $manager->flush();

        // Meetings

        $date = array(
            1 => array(
                'date_start'    => '2017-12-01 15:00:00',
                'date_end'      => '2017-12-01 17:00:00'
            ),
            2 => array(
                'date_start'    => '2018-07-15 08:00:00',
                'date_end'      => '2018-07-15 10:00:00'
            ),
            3 => array(
                'date_start'    => '2018-08-12 08:00:00',
                'date_end'      => '2018-08-12 17:30:00'
            ),
            4 => array(
                'date_start'    => '2018-07-12 08:00:00',
                'date_end'      => '2018-07-13 17:30:00'
            ),
            5 => array(
                'date_start'    => '2018-09-22 15:00:00',
                'date_end'      => '2018-09-22 18:30:00'
            )
        );

        $meetingsArray = array();

        for ($i = 1; $i <= 5; $i++) {
            $meeting = new Meeting();
            $meeting->setSprint($sprintsArray[rand(0, count($sprintsArray) - 1)]);
            $meeting->setProject($projectsArray[rand(0, count($projectsArray) - 1)]);
            $meeting->setName("Meeting " . $i);
            $meeting->setDescription("Description du meeting numero " . $i);
            $meeting->setOrganiser("Organiser " . $i);
            $meeting->setLocation("Location " . $i);
            $meeting->setTimeSpend(rand(20, 200));
            $meeting->setDateStart(new \DateTime($date[$i]['date_start']));
            $meeting->setDateEnd(new \DateTime($date[$i]['date_end']));
            $meeting->setCost(rand(50, 500));
            $meeting->setCreatedAt(new \DateTime('now'));
            $meeting->setCreatedBy($userArray[0]->getId());
            $meeting->setActive(true);

            $manager->persist($meeting);

            array_push($meetingsArray, $meeting);
        }

        $manager->flush();

        // Users projects

        // $k => Project ID - $v => User
        $usersProjects = array(
            1 => array(
                $userArray[0],
                $userArray[1],
                $userArray[2],
                $userArray[3]
            ),
            2 => array(
                $userArray[0],
                $userArray[1],
                $userArray[2],
                $userArray[3],
                $userArray[4]
            ),
            3 => array(
                $userArray[0],
                $userArray[1],
                $userArray[2],
                $userArray[3],
                $userArray[5]
            ),
            4 => array(
                $userArray[0],
                $userArray[1],
                $userArray[2],
                $userArray[4],
            ),
            5 => array(
                $userArray[0],
                $userArray[1],
                $userArray[2],
                $userArray[3],
                $userArray[4],
                $userArray[5]
            )
        );

        foreach ($usersProjects as $k => $v) {
            foreach ($v as $user) {
                $projectsArray[$k - 1]->addUser($user);
            }
            $manager->persist($projectsArray[$k - 1]);
        }
        $manager->flush();

        // $k => Meeting ID - $v => User
        $usersMeetings = array(
            1 => array(
                $userArray[0],
                $userArray[1],
                $userArray[3]
            ),
            2 => array(
                $userArray[0],
                $userArray[1],
                $userArray[2],
                $userArray[4]
            ),
            3 => array(
                $userArray[0],
                $userArray[1],
                $userArray[5]
            ),
            4 => array(
                $userArray[0],
                $userArray[2],
                $userArray[4],
            ),
            5 => array(
                $userArray[0],
                $userArray[1],
                $userArray[2],
                $userArray[3],
                $userArray[4],
                $userArray[5]
            )
        );

        foreach ($usersMeetings as $k => $v) {
            foreach ($v as $user) {
                $meetingsArray[$k - 1]->addUser($user);
            }
            $manager->persist($meetingsArray[$k - 1]);
        }
        $manager->flush();

        // $k => Meeting ID - $v => User
        $usersTasks = array(
            1 => array(
                $userArray[1],
                $userArray[3]
            ),
            2 => array(
                $userArray[2],
                $userArray[4]
            ),
            3 => array(
                $userArray[1],
                $userArray[5]
            ),
            4 => array(
                $userArray[2],
                $userArray[4],
            ),
            5 => array(
                $userArray[3],
                $userArray[4],
                $userArray[5]
            )
        );

        foreach ($tasksArray as $k => $task) {
            $project        = $task->getProject();
            $userProject    = $project->getUsers();
            $users          = array();
            foreach ($userProject as $user) {
                array_push($users, $user);
            }
            $randInt = rand(1, count($users));
            $randomUsers = array_rand($users, $randInt);
            if (is_array($randomUsers)) {
                foreach ($randomUsers as $randUser) {
                    $task->addUser($users[$randUser]);
                }
            } else {
                $task->addUser($users[$randomUsers]);
            }

            $manager->persist($task);
        }
        $manager->flush();

    }
}