<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/07/2018
 * Time: 16:27
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Return CSRF Token
     *
     * @Route("/login-generate-token", name="login-generate-token")
     */
    public function generateTokerAction()
    {
        $csrf = $this->get('security.csrf.token_manager')->getToken('authenticate');

        return new Response($csrf);

    }

}