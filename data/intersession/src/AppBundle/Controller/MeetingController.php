<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
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
     * @Rest\Post("/projects/{id}/meetings")
     */
    public function postMeetingsAction(Request $request)
    {
        $meetings = new Meeting();
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        if (empty($project)) {
            return \FOS\RestBundle\View\View::create(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }
        else {
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
                $meetings->setTimeSpend($endAt - $startAt);

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
    }

    /**
     * @Rest\View(serializerGroups={"meeting"})
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
     * @Rest\View(serializerGroups={"meeting"})
     * @Rest\Get("/meetings")
     */
    public function getAllMeetingsAction(Request $request)
    {
        $meetings = $this->getDoctrine()->getRepository(Meeting::class)->findAll();

        return $meetings;
    }
    /**
     * @Rest\View(serializerGroups={"meeting"})
     * @Rest\Get("/users/{id}/meetings")
     */
    public function getAllMeetingsByUserAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));

        if ($user) {
            return $user->GetMeetings();
        } else {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);

        }
    }
    /**
     * @Rest\View(serializerGroups={"meeting"})
     * @Rest\Get("/projects/{id}/meetings")
     */
    public function getAllMeetingsByProjectAction(Request $request)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($request->get('id'));

        if ($project) {
            return $project->GetMeetings();
        } else {
            return \FOS\RestBundle\View\View::create(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @Rest\View(serializerGroups={"meeting"})
     * @Rest\Get("/projects/{projectId}/users/{userId}/meetings")
     */
    public function getAllMeetingsByProjectAndUserAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('userId'));

        if ($user) {
            $meetings = $user->getMeetings();
            $registeredMeetings = [];
            foreach( $meetings as $meeting){
                if ($meeting->getProject() == $request->get('projectId')){
                    $registeredMeetings[] = $meeting;
                }
            }
            return $registeredMeetings;
        }
        else {
            return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
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
        return $this->updateMeeting($request, false);
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