<?php // src/AppBundle/Controller/JobStatusController.php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Form\Type\JobType;
use AppBundle\Entity\Job;
use AppBundle\Entity\User;

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
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($request->get('id'));

        if (empty($user)){
            return $this->userNotFound();
        }
        return $user->getJob();
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"job"})
     * @Rest\Post("/jobs")
     */
    public function postJobAction(Request $request){

        $job = new Job();
        $form = $this->createForm(JobType::class, $job);

        $form->submit($request->request->all());

        if ($form->isValid()){
            $job->setCreatedAt(new \DateTime('now'));
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