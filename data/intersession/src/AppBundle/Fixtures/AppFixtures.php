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
        foreach ($jobsName as $jobName) {
            $job = new Job();
            $job->setName($jobName);
            $job->setCreatedAt(new \DateTime('now'));

            $manager->persist($job);
            ${$prefix . $count} = $job;
            $count++;
        }
        $manager->flush();

        // User
        $users = array(
            1 => array(
                "firstname" => "Admin",
                "lastname"  => "Admin",
                "email"     => "admin@truite-tracker.com",
                "password"  => "12345",
                "job"       => $job_1
            ),
            2 => array(
                "firstname" => "LD",
                "lastname"  => "LD",
                "email"     => "ld@truite-tracker.com",
                "password"  => "12345",
                "job"       => $job_2
            ),
            3 => array(
                "firstname" => "CP",
                "lastname"  => "CP",
                "email"     => "cp@truite-tracker.com",
                "password"  => "12345",
                "job"       => $job_3
            ),
            4 => array(
                "firstname" => "Dev",
                "lastname"  => "Dev",
                "email"     => "dev@truite-tracker.com",
                "password"  => "12345",
                "job"       => $job_4
            ),
        );

        foreach ($users as $k => $v) {
            $user = new User();
            $user->setEmail($v['email']);
            $user->setLastname($v['lastname']);
            $user->setFirstname($v['firstname']);
            $user->setJob($v['job']);
            $user->setPassword('$2y$12$lHlUj31auOmaDuFHQU.EheSTAFM.S6GVrNJC8LHU5yKf2kIXpJ.Q.');

            $manager->persist($user);
        }



        $manager->flush();
    }
}