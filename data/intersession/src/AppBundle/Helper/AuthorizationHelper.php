<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/07/2018
 * Time: 12:00
 */

namespace AppBundle\Helper;


use AppBundle\Entity\User;
use AppBundle\Entity\AuthToken;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class AuthorizationHelper
 * @package AppBundle\Helper
 */
class AuthorizationHelper
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Container
     */
    private $container;

    /**
     * Authorization constructor.
     * @param EntityManager $entityManager
     * @param Container $container
     */
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em           = $entityManager;
        $this->container    = $container;
    }


    /**
     * Get current connected User
     *
     * @return null
     */
    public function getCurrentUser()
    {
        $request = Request::createFromGlobals();

        $authTokenHeader = $request->headers->get('X-Auth-Token');

        $token = $this->em->getRepository(AuthToken::class)->findOneBy(array(
            'value' => $authTokenHeader,
        ));

        if ($token) {
            return $token->getUser();
        } else {
            return null;
        }
    }

    /**
     * Return true if current user is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        $user = $this->getCurrentUser();

        if ($user->getJob()->getId() === 1) {
            return true;
        } else {
            return false;
        }
    }



}