<?php
// src/AppBundle/Entity/Role.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;


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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="protectedRoles")
     */
    private $user;


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

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Role
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
