<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/07/2018
 * Time: 14:47
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Form\Type\MeetingType;
use FOS\RestBundle\FOSRestBundle;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Form\Type\UserType;
use AppBundle\Controller\BaseController;

class MeetingController extends BaseController
{
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"meeting"})
     * @Rest\Post("/meetings/{id}")
     */
    public function postMeetingsAction(Request $request, Project $project)
    {
        $meetings = new Meeting();

        $form = $this->createForm(MeetingType::class, $meetings, ['validation_groups'=>['Default', 'New']]);
        $form->submit($request->request->all());

        $startAt = $this->stringToDatetime($request->request->all()['startAt']);
        $endAt = $this->stringToDatetime($request->request->all()['endAt']);

        if ($form->isValid() && $startAt && $endAt) {
            $meetings->setCreatedAt(new \DateTime('now'));
            $meetings->setCreatedBy($this->getUser()->getId());
            $meetings->setProject($project);
            $meetings->setDateStart($startAt);
            $meetings->setDateEnd($endAt);
            $meetings->setTimeSpend(0);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($meetings);
            $em->flush();

            return $meetings;
        } elseif (!$startAt || !$endAt) {
            return View::create(["message" => "Le format des dates n'est pas compatible."], 500);
        } else {
            return $form;
        }

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"meeting"})
     * @Rest\Get("/meetings/{id}")
     */
    public function getMeetingsAction(Request $request, $id)
    {
        $meeting = $this->getDoctrine()->getRepository(Meeting::class)->find($id);

        if ($meeting) {
            return $meeting;
        } else {
            return $this->meetingNotFound();
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"meeting"})
     * @Rest\Get("/meetings")
     */
    public function getAllMeetingsAction(Request $request)
    {
        $meetings = $this->getDoctrine()->getRepository(Meeting::class)->findAll();

        return $meetings;
    }

    /**
     * @Rest\View(serializerGroups={"meeting"})
     * @Rest\Put("/meetings/{id}")
     */
    public function updateMeetingAction(Request $request)
    {
        return $this->updateMeeting($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"meeting"})
     * @Rest\Patch("/meetings/{id}")
     */
    public function patchMeetingAction(Request $request)
    {
        return $this->updateMeeting($request, true);
    }

    /**
     * @param Request $request
     * @param $clearMissing
     * @return User|\FOS\RestBundle\View\View|\Symfony\Component\Form\FormInterface
     */
    private function updateMeeting(Request $request, $clearMissing)
    {
        $meeting = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Meeting')
            ->find($request->get('id'));
        /* @var $meeting Meeting */

        if (empty($meeting)) {
            return $this->meetingNotFound();
        }

        if ($clearMissing) {
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = [];
        }

        $form = $this->createForm(MeetingType::class, $meeting, $options);

        $form->submit($request->request->all(), $clearMissing);

        $startAt = $this->stringToDatetime($request->request->all()['startAt']);
        $endAt = $this->stringToDatetime($request->request->all()['endAt']);


        if ($form->isValid() && $startAt && $endAt) {
            $meeting->setDateStart($startAt);
            $meeting->setDateEnd($endAt);
            $meeting->setTimeSpend(0);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->merge($meeting);
            $em->flush();
            return $meeting;
        } elseif (!$startAt || !$endAt) {
            return View::create(["message" => "Le format des dates n'est pas compatible."], 500);
        } else {
            return $form;
        }
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    private function meetingNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Meeting not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/meetings/{id}")
     */
    public function removeMeetingAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $meeting = $em->getRepository('AppBundle:Meeting')
            ->find($request->get('id'));
        /* @var $user User */

        if ($meeting) {
            $em->remove($meeting);
            $em->flush();

            return View::create(["message" => "Le meeting à bien été supprimé."]);
        } else {
            return $this->meetingNotFound();
        }
    }

}