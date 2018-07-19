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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Serializer;

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
    public function normalizeJSON($obj) {
        $encoder = new JsonEncoder();
        $normalizer = new JsonSerializableNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer(array($normalizer), array($encoder));

        return $serializer->serialize($obj, 'json');
    }

}