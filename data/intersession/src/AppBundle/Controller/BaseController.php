<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/07/2018
 * Time: 12:01
 */

namespace AppBundle\Controller;

use AppBundle\Helper\AuthorizationHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Helper\JSONHelper;

abstract class BaseController extends Controller
{
    /**
     * @var JSONHelper
     */
    protected $JSONHelper;

    /**
     * @var AuthorizationHelper
     */
    protected $authorizationHelper;

    /**
     * BaseController constructor.
     * @param JSONHelper $JSONHelper
     */
    public function __construct(JSONHelper $JSONHelper, AuthorizationHelper $authorizationHelper)
    {
        $this->JSONHelper           = $JSONHelper;
        $this->authorizationHelper  = $authorizationHelper;
    }

    /**
     * Return current user
     *
     * @return mixed|null
     */
    public function getUser()
    {
        return $this->authorizationHelper->getCurrentUser();
    }

}