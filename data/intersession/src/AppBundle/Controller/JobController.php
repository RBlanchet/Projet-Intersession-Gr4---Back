<?php // src/AppBundle/Controller/JobStatusController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\Type\JobType;
use AppBundle\Entity\Job;

class JobController extends Controller {

    /**
     * @Rest\View(serializerGroups={"job"})
     * @Rest\Get("/jobs")
     */
    public function getJobsAction(Request $request)
    {
        $jobs = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Job')
            ->findAll();
        /* @var $jobs Job[] */
        return $jobs;
    }

    /**
     * @Rest\View(serializerGroups={"job"})
     * @Rest\Get("/users/{id}/jobs")
     */
    public function getJobAction(Request $request){
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));

        if (empty($user)){
            return $this->userNotFound();
        }
        return $user;
    }

    /**
     * @Rest\View(serializerGroups={"job"}, statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/jobs")
     */
    public function postJobsAction(Request $request){
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));

        if(empty($user)){
            return $this->userNotFound();
        }

        $job = new Job();
        $job->setUser($user);

        $form = $this->createForm(JobType::class, $job);

        $form->submit($request->request->all());

        if ($form->isValid()){
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($job);
            $em->flush();
            return $job;
        }
        else{
            return $form;
        }
    }

    private function userNotFound(){
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
}