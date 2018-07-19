<?php
// src/AppBundle/Entity/Task.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */

class Task {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $cost;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="float")
     */
    private $timeSpend;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="tasks")
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sprint", inversedBy="tasks")
     */
    private $sprint;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinTable(name="users_tasks")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Task", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Task", mappedBy="parent")
     */
    private $children;



    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->children = new ArrayCollection();
    }
    public function jsonSerialize()
    {
        return array(
            'project' . $this->id => array(
                'id'            => $this->id,
                'project'       => $this->project,
                'name'          => $this->name,
                'cost'          => $this->cost,
                'sprint'        => $this->sprint,
                'description'   => $this->description,
                'timeSpend'     => $this->timeSpend,
                'dateStart'     => $this->dateStart,
                'dateEnd'       => $this->dateEnd,
                'isActive'      => $this->active,
                'status'        => $this->status,
                'parent'        => $this->parent,
            )
        );
    }

    public function getRelations()
    {
        return array(
            'children'      => $this->getChildren(),
            'users'         => $this->getUsers(),
        );
    }
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addUser(User $user)
    {
        $user->addTask($this);
        $this->users[] = $user;
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
     * Set name.
     *
     * @param string $name
     *
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cost.
     *
     * @param float $cost
     *
     * @return Task
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
     * Set dateStart.
     *
     * @param \DateTime $dateStart
     *
     * @return Task
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart.
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd.
     *
     * @param \DateTime $dateEnd
     *
     * @return Task
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd.
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Task
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdBy.
     *
     * @param int $createdBy
     *
     * @return Task
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set timeSpend.
     *
     * @param float $timeSpend
     *
     * @return Task
     */
    public function setTimeSpend($timeSpend)
    {
        $this->timeSpend = $timeSpend;

        return $this;
    }

    /**
     * Get timeSpend.
     *
     * @return float
     */
    public function getTimeSpend()
    {
        return $this->timeSpend;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Task
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set project.
     *
     * @param \AppBundle\Entity\Project|null $project
     *
     * @return Task
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return \AppBundle\Entity\Project|null
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set sprint.
     *
     * @param \AppBundle\Entity\Sprint|null $sprint
     *
     * @return Task
     */
    public function setSprint(\AppBundle\Entity\Sprint $sprint = null)
    {
        $this->sprint = $sprint;

        return $this;
    }

    /**
     * Get sprint.
     *
     * @return \AppBundle\Entity\Sprint|null
     */
    public function getSprint()
    {
        return $this->sprint;
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
     * Set parent.
     *
     * @param \AppBundle\Entity\Task|null $parent
     *
     * @return Task
     */
    public function setParent(\AppBundle\Entity\Task $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \AppBundle\Entity\Task|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child.
     *
     * @param \AppBundle\Entity\Task $child
     *
     * @return Task
     */
    public function addChild(\AppBundle\Entity\Task $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \AppBundle\Entity\Task $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(\AppBundle\Entity\Task $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
