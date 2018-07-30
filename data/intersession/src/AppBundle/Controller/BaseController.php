<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/07/2018
 * Time: 12:01
 */

namespace AppBundle\Controller;

use AppBundle\Helper\AuthorizationHelper;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Helper\JSONHelper;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Return true if user is Admin
     *
     * @return mixed|null
     */
    public function isAdmin()
    {
        return $this->authorizationHelper->isAdmin();
    }

    /**
     * Convert string 'YYYY-MM-DD hh:mm:ss' to object Datetime
     *
     * @param $dateString
     * @return bool
     */
    public function stringToDatetime($dateString)
    {
        $datetime = new \DateTime($dateString);

        if ($datetime) {
            return $datetime;
        } else {
            return false;
        }
    }

    /**
     * Calculate Hours Spend
     *
     * @param $dateStart
     * @param $dateEnd
     * @param $users
     * @return float|int
     */
    public function timeSpend($dateStart, $dateEnd, $users){
        $delta = date_diff($dateEnd,  $dateStart);
        $days = $delta->format("%a");
        if ($days){
            if ($days >= 7){
                $weeks = floor($days/7);
                $weekEnds = 2*$weeks;
                $days -= $weekEnds;
            }
        }
        return ($days * 7) * $users;
    }

    public function checkDateValidate($dateStart, $dateEnd) {
        if (is_object($dateStart) && is_object($dateEnd)) {
            if ($dateEnd > $dateStart) {
                return true;
            } else {
                return ['message' => 'La date de fin est avant la date de départ'];
            }
        } else {
            return ['message' => 'Le format de date attendu doit être un Datetime'];
        }
    }

    public function isActived($item) {
        return $item->getActive();
    }

    public function isDesactivated($item) {
        return !$item->getActive();
    }

    public function errorMessage($message)
    {
        return \FOS\RestBundle\View\View::create(['message' => $message], Response::HTTP_BAD_REQUEST);
    }

    public function sendMail($user){
        $mailer = $this->get('mailer');
        $message = (new \Swift_Message('Un mail'))
            ->setFrom('delaporte.maxime@orange.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'registration.html.twig',
                    array(
                        'name' => $user->getFirstname(),
                        'email' =>$user->getEmail(),
                        'password' => $user->getplainPassword())
                ),
                'text/html'
            );
        $mailer->send($message);
        return \FOS\RestBundle\View\View::create(['message' => 'Email send'], Response::HTTP_OK);
    }
}

