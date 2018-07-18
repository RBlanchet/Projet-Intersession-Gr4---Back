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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Project", mappedBy="admin")
     */
    private $projects;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Task", mappedBy="tasks")
     */
    private $tasks;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role", inversedBy="users")
     */

    private $protectedRoles;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Job")
     * @ORM\JoinTable(name="users_jobs",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="job_id", referencedColumnName="id")}
     *      )
     */
    private $jobs;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Meeting", mappedBy="users")
     */
    private $meetings;

    public function __construct()
    {
        parent::__construct();
        // Logic to call entity

        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->jobs = new ArrayCollection();
        $this->protectedRoles = new ArrayCollection();
        $this->meetings = new ArrayCollection();
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

    public function getProtectedRoles(): Collection
    {

        return $this->protectedRoles;

    }
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

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