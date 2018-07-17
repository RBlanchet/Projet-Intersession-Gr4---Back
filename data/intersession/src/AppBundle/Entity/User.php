<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Project", mappedby="admin")
     */
    private $projects;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Task", mappedBy="tasks")
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Job", mappedby="user")
     */
    private $jobs;




    public function __construct()
    {
        parent::__construct();
        // Logic to call entity

        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }

    public function getProjects(): Collection
    {
        return $this->projects;
    }
    public function getTasks(): Collection
    {
        return $this->tasks;
    }
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return array(
            'user' . $this->id => array(
                'id'        => $this->id,
                'username'  => $this->username,
            )
        );
    }

    public function getRelations() {
        return array();
    }
}