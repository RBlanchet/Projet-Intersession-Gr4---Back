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
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Task", mappedBy="users")
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
                'id'                => $this->getId(),
                'username'          => $this->getUsername(),
                'protectedRoles'    => $this->getProtectedRoles(),
                'email'             => $this->getEmail()
            )
        );
    }

    public function getRelations() {
        return array(
            'projects'      => $this->getProjects(),
            'tasks'         => $this->getTasks(),
            'protectedRoles'  => $this->getProtectedRoles(),
            'jobs'          => $this->getJobs(),
            'meetings'      => $this->getMeetings()
        );
    }

    /**
     * Add project.
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return User
     */
    public function addProject(\AppBundle\Entity\Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project.
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProject(\AppBundle\Entity\Project $project)
    {
        return $this->projects->removeElement($project);
    }

    /**
     * Add task.
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return User
     */
    public function addTask(\AppBundle\Entity\Task $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task.
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTask(\AppBundle\Entity\Task $task)
    {
        return $this->tasks->removeElement($task);
    }

    /**
     * Add protectedRole.
     *
     * @param \AppBundle\Entity\Role $protectedRole
     *
     * @return User
     */
    public function addProtectedRole(\AppBundle\Entity\Role $protectedRole)
    {
        $this->protectedRoles[] = $protectedRole;

        return $this;
    }

    /**
     * Remove protectedRole.
     *
     * @param \AppBundle\Entity\Role $protectedRole
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProtectedRole(\AppBundle\Entity\Role $protectedRole)
    {
        return $this->protectedRoles->removeElement($protectedRole);
    }

    /**
     * Add job.
     *
     * @param \AppBundle\Entity\Job $job
     *
     * @return User
     */
    public function addJob(\AppBundle\Entity\Job $job)
    {
        $this->jobs[] = $job;

        return $this;
    }

    /**
     * Remove job.
     *
     * @param \AppBundle\Entity\Job $job
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeJob(\AppBundle\Entity\Job $job)
    {
        return $this->jobs->removeElement($job);
    }

    /**
     * Add meeting.
     *
     * @param \AppBundle\Entity\Meeting $meeting
     *
     * @return User
     */
    public function addMeeting(\AppBundle\Entity\Meeting $meeting)
    {
        $this->meetings[] = $meeting;

        return $this;
    }

    /**
     * Remove meeting.
     *
     * @param \AppBundle\Entity\Meeting $meeting
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMeeting(\AppBundle\Entity\Meeting $meeting)
    {
        return $this->meetings->removeElement($meeting);
    }
}
