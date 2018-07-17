<?php
// src/AppBundle/Entity/Meeting.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="meetings")
 */

class Meeting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string" length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string" length=255)
     */
    private $organiser;

    /**
     * @ORM\Column(type="string" length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="double")
     */
    private $timeSpend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="timestamp")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdBy;
}