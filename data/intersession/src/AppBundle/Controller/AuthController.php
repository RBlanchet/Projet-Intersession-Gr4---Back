<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/07/2018
 * Time: 16:27
 */

namespace AppBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
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

    /**
     * Return CSRF Token
     *
     * @Route("/me", name="me")
     */
    public function getMe()
    {
        $user = $this->getUser();
        if ($user) {
            $jsonNormalize = $this->JSONHelper->normalizeJSON($user);
            return new Response($jsonNormalize, 200);
        } else {
            return new Response(null, 404);
        }
    }

}