<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/07/2018
 * Time: 14:18
 */

namespace AppBundle\Controller;

// Base Controller
use AppBundle\Controller\BaseController;

// Routing
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

// Request and Response
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/user", name="user")
 */
class UserController extends BaseController
{
    /**
     * @Route("/", name="_index")
     * @Method({"POST"})
     */
    public function userFormCreateAction()
    {
    }

    /**
     * @Route("/", name="_getAllUser")
     * @Method({"GET"})
     */
    public function userGetAllAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return new Response($this->JSONHelper->normalizeJSON($users), 200);
    }

    /**
     * @Route("/create", name="_create")
     */
    public function userCreateAction()
    {
        dump($this->getUser()->getJobs()); die;
        $roles = $this->authorizationHelper->getRolesCreateUser($this->getUser()->getJobs()); die;

    }

    /**
     * @Route("/{id}", name="_get")
     * @Method({"GET"})
     */
    public function userGetAction(User $user)
    {
        return new Response(json_encode($this->JSONHelper->normalizeJSON($user)));
    }





}