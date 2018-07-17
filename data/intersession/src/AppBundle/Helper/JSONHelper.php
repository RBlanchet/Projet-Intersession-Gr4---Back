<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 17/07/2018
 * Time: 11:33
 */

namespace AppBundle\Helper;

use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class JSONHelper
 * @package AppBundle\Helper
 */
final class JSONHelper
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
     * JSONHelper constructor.
     * @param EntityManager $entityManager
     * @param Container $container
     */
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em           = $entityManager;
        $this->container    = $container;
    }

    /**
     * Format to JSON Normalize
     *
     * @param $entityArray
     * @return array
     */
    public function normalizeJSON($entityArray) {
        $jsonNormalize = array();
        foreach ($entityArray as $key => $value) {
            $allId = array();
            foreach ($value as $v) {
                array_push($allId, $key . $v->getId());
            }
            $jsonNormalize[$key] = array(
                'byId'  => $value,
                'allId' => $allId
            );
            // Each on entity
            foreach ($value as $element) {
                // Get relation on current Entity
                foreach ($element->getRelations() as $entityKey => $entityValue) {
                    // If AllId and ById not exist, create
                    if (!isset($jsonNormalize[$entityKey])) {
                        $jsonNormalize[$entityKey]['byId'] = array();
                        $jsonNormalize[$entityKey]['allId'] = array();
                    }
                    // If not in array, insert
                    if (!in_array($entityKey . $entityValue->getId(), $jsonNormalize[$entityKey]['allId'])){
                        array_push($jsonNormalize[$entityKey]['byId'], $entityValue);
                        array_push($jsonNormalize[$entityKey]['allId'], $entityKey . $entityValue->getId());
                    }
                }
            }
        }

        return $jsonNormalize;
    }

}