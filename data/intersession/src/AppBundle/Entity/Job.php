<?php
// src/AppBundle/Entity/Job

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Job
 * @ORM\Entity
 * @ORM\Table(name="jobs")
 */
class Job {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="jobs")
     */
    private $user;

    /**
     * @ORM\Column(type="double")
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="jobs")
     */
    private $role;
}