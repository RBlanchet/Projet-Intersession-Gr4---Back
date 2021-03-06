<?php

namespace AppBundle\Controller;

// Base Controller
use AppBundle\Controller\BaseController;

//Repository
use AppBundle\Entity\Job;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

// Routing
use AppBundle\Entity\Project;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;

// Request and Response
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use AppBundle\Form\Type\ProjectType;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;

/**
 * Class ProjectController
 * @package AppBundle\Controller
 */
class ProjectController extends BaseController
{
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"project"})
     * @Rest\Post("/projects")
     */
    public function postProjectAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project, ['validation_groups'=>['Default', 'New']]);

        $role = new Role();

        $data = $request->request->all();

        $data['date_start'] = $this->stringToDatetime($request->request->all()['date_start']);
        $data['date_end']   = $this->stringToDatetime($request->request->all()['date_end']);

        $form->submit($data);

        $validate = $this->checkDateValidate($data['date_start'], $data['date_end'] );

        if ($form->isValid() && !is_array($validate)) {
            $em = $this->get('doctrine.orm.entity_manager');
            // Project
            $project->setCreatedAt(new \DateTime('now'));
            $project->setCreatedBy($this->getUser()->getId());
            $project->setActive(true);
            $project->addUser($this->getUser());

            $job = $em->getRepository(Job::class)->find(1);

            if (isset($request->request->all()['hour_pool'])) {
                $project->setHourPool($request->request->all()['hour_pool']);
            }
              else {
                $project->setHourPool($this->timeSpend($dateStart, $dateEnd));
            }
            $project->setHourSpend(0);

            $em->persist($project);

            $em->flush();

            $role = new Role();
            $role->setProject($project);
            $role->setUser($this->getUser());
            $role->setCost(0);
            $role->setJob($job);

            $em->persist($role);

            $em->flush();

            // Job
            $job = $em->getRepository('AppBundle:Job')->find(1);

            return $project;
        } elseif (is_array($validate)) {
            return View::create($validate, 401);
        } else {
            return $form;
        }
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_OK)
     * @Rest\Delete("/projects/{id}")
     */
    public function removeProjectAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $project = $em->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        /* @var $project Project */

        if ($project) {
            if ($project->getActive()) {
                $message = 'Le projet à bien été desactivé';
                $project->setActive(false);
            } else {
                $message = 'Le projet à bien été activé';
                $project->setActive(true);
            }
            $em->persist($project);
            $em->flush();
            return View::create(["message" => $message]);
        } else {
            return $this->projectNotFound();
        }
    }


    /**
     * @Rest\View(serializerGroups={"project"})
     * @Rest\Patch("/projects/{id}")
     */

    public function patchProjectAction(Request $request)
    {
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        /* @var $project Project */

        if (empty($project)) {
            return $this->projectNotFound();
        }

        $form = $this->createForm(ProjectType::class, $project);

        $form->submit($request->request->all(), false);
        if (isset($request->request->all()['active'])){
            $active = $request->request->all()['active'];
        }
        else{
            $active = true;
        }

        if (array_key_exists('date-start', $request->request->all())) {
            $startAt = $this->stringToDatetime($request->request->all()['date_start']);
        }
        if (array_key_exists('date_end', $request->request->all())) {
            $endAt =$this->stringToDatetime($request->request->all()['date_end']);
        }
        if (array_key_exists( 'cost',$request->request->all())){
            $cost = $request->request->all()['cost'];
        }
        if (array_key_exists('hour_spend',$request->request->all())){
            $hours = $request->request->all()['hour_spend'];
        }
        if (array_key_exists('hour_pool',$request->request->all())){
            $pool = $request->request->all()['hour_pool'];
        }
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $users = count($project->getUsers());
            if ($active == false){
                $this->exportPDF($project);
            }
            if ($startAt) {
                $project->setDateStart($startAt);
            }
            if ($endAt) {
                $project->setDateEnd($endAt);
            }
            if (!$pool && ($startAt || $endAt)){
                $project->setHourPool($this->timeSpend($this->stringToDatetime($project->getDateStart()),$this->stringToDatetime($project->getDateEnd())));
            }
            if ($hours){
                $project->setHourSpend($hours);
            }
            else {
                $hours = 0;
                $double = false;
                if(!$cost){
                    $cost = 0;
                    $double = true;
                }
                foreach($project->getTasks() as $task)
                {
                    $hours += $task->getTimeSpend();
                    if($double){
                        $cost += $task->getCost();
                    }
                }
                $project->setHourSpend($hours);
                if ($cost != 0){
                    $project->setCost($cost);
                }
            }
            if ($cost !=""){
                $project->setCost($cost);
            }
            $em->merge($project);
            $em->flush();

            return $project;
        }
        else {
            return $form;
        }
    }


    /**
     * @return \FOS\RestBundle\View\View
     */
    private function projectNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"project"})
     * @Rest\Get("/projects/{id}")
     */
    public function getProjectAction(Request $request)
    {
        $projects = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id'));
        if ($projects) {
            if ($this->isActived($projects)) {
                return $projects;
            } else {
                return $this->errorMessage('Ce projet est non actif');
            }
        } else {
            return $this->projectNotFound();
        }
        return $projects;
    }
    /**
     * @Rest\View(serializerGroups={"project"})
     * @Rest\Get("/projects/{id}/users")
     */
    public function getUsersByProjectAction(Request $request){
        $project = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
        /* @var $project Project */
        if (empty($project)) {
            return $this->projectNotFound();
        }
        return $project->getUsers();
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"project"})
     * @Rest\Get("/projects/pdf/{id}")
     */
    public function getPdfProjectAction(Project $project)
    {
        $html = $this->renderView('facturation.html.twig',
            array(
                'budget'    => $project->getPrice(),
                'cost'      => $project->getCost(),
                'name'      => $project->getName(),
                'start'     => $project->getDateStart(),
                'end'       => $project->getDateEnd(),
                'duration'  => $project->getHourSpend(),
                'pool'      => $project->getHourPool(),
                'team'      => $project->getUsers(),
                'actif'      => $project->getActive(),
                'export_date' => new \DateTime('now')
            ));

        $filename = sprintf('Repporting-%s.pdf', date('Y-m-d'));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }


    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"projects"})
     * @Rest\Get("/projects")
 */
    public function getProjectsAction(Request $request, ParamFetcher $paramFetcher)
    {
//        $offset = $paramFetcher->get('offset');
//        $limit = $paramFetcher->get('limit');
//        $active = $paramFetcher->get('active');
//        $sort = $paramFetcher->get('sort');
//
//        $qb = $this->get('doctrine.orm.entity_manager')
//            ->createQueryBuilder();
//            $qb->select('p')
//            ->from('AppBundle:Project', 'p');
//        if ($offset != "") {
//            $qb->setFirstResult($offset);
//        }
//        if ($active != ""){
//            $qb->setActive($active);
//        }
//        if (in_array($sort, ['asc', 'desc'])) {
//            $qb->orderBy('p.name', $sort);
//        }
//
//        if ($limit != "") {
//            $qb->setMaxResults($limit);
//        }
//
//        $projects = $qb->getQuery()->getResult();
//
//        return $projects;
        $projects = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Project')
            ->findAll();
        return $projects;
    }

//    public function exportPDF(Project $project){
//        $snappy = $this->get('knp_snappy.pdf');
//        $html = $this->renderView('facturation.html.twig', array(
//            'budget'    => $project->getPrice(),
//            'cost'      => $project->getCost(),
//            'name'      => $project->getName(),
//            'start'     => $project->getDateStart(),
//            'end'       => $project->getDateEnd(),
//            'duration'  => $project->getHourSpend(),
//        ));
//
//        $filename = "exportProject" . $project->getName();
//
//        return new Response(
//            $snappy->getOutputFromHtml($html),
//            200,
//            array(
//                'Content-Type'          =>'application/pdf',
//                'Content-Disposition'   => 'inline; filename="' . $filename.'.pdf"'
//            )
//        );
//    }
}