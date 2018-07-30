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
//        $delta = date_diff($dateEnd,  $dateStart);
//        $days = $delta->format("%a");
//        if ($days){
//            if ($days >= 7){
//                $weeks = floor($days/7);
//                $weekEnds = 2*$weeks;
//                $days -= $weekEnds;
//            }
//        }
//        return ($days * 7) * $users;
        $dateStart = strtotime($dateStart);
        $dateEnd = strtotime($dateEnd);
        $days = ($dateEnd - $dateStart) / 86400 + 1;
        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);
        //It will return 1 if it's Monday,.. ,7 for Sunday
        $the_first_day_of_week = date("N", $dateStart);
        $the_last_day_of_week = date("N", $dateEnd);
        //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
        //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            // (edit by Tokes to fix an edge case where the start day was a Sunday
            // and the end day was NOT a Saturday)
            // the day of the week for start is later than the day of the week for end
            if ($the_first_day_of_week == 7) {
                // if the start date is a Sunday, then we definitely subtract 1 day
                $no_remaining_days--;
                if ($the_last_day_of_week == 6) {
                    // if the end date is a Saturday, then we subtract another day
                    $no_remaining_days--;
                }
            }
            else {
                // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                // so we skip an entire weekend and subtract 2 days
                $no_remaining_days -= 2;
            }
        }
        //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
        $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
            $workingDays += $no_remaining_days;
        }

        return ($workingDays * 7)* $users;
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

