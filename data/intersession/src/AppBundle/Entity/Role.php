<?php
// src/AppBundle/Entity/Role.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class Job
 * @ORM\Entity
 * @ORM\Table(name="roles")
 */
class Role {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     */
    private $project;

    /**
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Job")
     */
    private $job;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", mappedBy="protectedRoles")
     */

    private $users;


    public function __construct() {
        $this->users = new ArrayCollection();
    }
    public function jsonSerialize()
    {
        return array(
            'project' . $this->id => array(
                'id'            => $this->id,
                'job'           => $this->job,
                'project'       => $this->project,
                'cost'          => $this->cost,
            )
        );
    }

    public function getRelations()
    {
        return array(
            'users'         => $this->getUsers(),
        );
    }
    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set project.
     *
     * @param int $project
     *
     * @return Role
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return int
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set cost.
     *
     * @param float $cost
     *
     * @return Role
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost.
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Add user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Role
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set job.
     *
     * @param \AppBundle\Entity\Job|null $job
     *
     * @return Role
     */
    public function setJob(\AppBundle\Entity\Job $job = null)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job.
     *
     * @return \AppBundle\Entity\Job|null
     */
    public function getJob()
    {
        return $this->job;
    }
}
