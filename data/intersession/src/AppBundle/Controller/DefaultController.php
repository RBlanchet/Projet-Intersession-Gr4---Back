<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Test;
use AppBundle\Entity\User;
use AppBundle\Helper\JSONHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @var JSONHelper
     */
    private $JSONHelper;

    /**
     * DefaultController constructor.
     * @param JSONHelper $JSONHelper
     */
    public function __construct(JSONHelper $JSONHelper)
    {
        $this->JSONHelper = $JSONHelper;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * Example to export json normalize
     *
     * @Route("/example-json", name="example-json")
     */
    public function helloWorldAction() {

        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        $jsonNormalize = $this->JSONHelper->normalizeJSON(array(
            'user' => $users
        ));

        $response = new Response(
            json_encode($jsonNormalize)
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
