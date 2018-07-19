<?php
// src/AppBundle/Entity/Project.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="projects")
 */

class Project
{
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $admin;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

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
     * @ORM\Column(type="float")
     */
    private $hourPool;

    /**
     * @ORM\Column(type="float")
     */
    private $hourSpend;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Task", mappedBy="project")
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Sprint", mappedBy="project")
     */
    private $sprints;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="projects")
     * @ORM\JoinTable(name="users_projects")
     */
    private $users;



    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->sprints = new ArrayCollection();
        $this->users = new ArrayCollection();
    }
    public function jsonSerialize()
    {
        return array(
            'project' . $this->id => array(
                'id'            => $this->id,
                'admin'         => $this->admin,
                'name'          => $this->name,
                'cost'          => $this->cost,
                'description'   => $this->description,
                'hourPool'      => $this->hourPool,
                'hourSpend'     => $this->hourSpend,
                'price'         => $this->price,
                'dateStart'     => $this->dateStart,
                'dateEnd'       => $this->dateEnd,
                'isActive'      => $this->active,
            )
        );
    }

    public function getRelations()
    {
        return array(
            'sprints'      => $this->getSprints(),
            'tasks'         => $this->getTasks(),
            'meetings'      => $this->getMeetings(),
            'users'         => $this->getUsers(),
        );
    }
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getSprints(): Collection
    {
        return $this->sprints;
    }
    public function getUsers(): Collection
    {
        return $this->users;
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
     * @return Project
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
     * Set description.
     *
     * @param string $description
     *
     * @return Project
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

    /**
     * Set price.
     *
     * @param float $price
     *
     * @return Project
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set cost.
     *
     * @param float $cost
     *
     * @return Project
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
     * @return Project
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
     * @return Project
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
     * Set hourPool.
     *
     * @param float $hourPool
     *
     * @return Project
     */
    public function setHourPool($hourPool)
    {
        $this->hourPool = $hourPool;

        return $this;
    }

    /**
     * Get hourPool.
     *
     * @return float
     */
    public function getHourPool()
    {
        return $this->hourPool;
    }

    /**
     * Set hourSpend.
     *
     * @param float $hourSpend
     *
     * @return Project
     */
    public function setHourSpend($hourSpend)
    {
        $this->hourSpend = $hourSpend;

        return $this;
    }

    /**
     * Get hourSpend.
     *
     * @return float
     */
    public function getHourSpend()
    {
        return $this->hourSpend;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Project
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
     * @return Project
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
     * Set active.
     *
     * @param bool $active
     *
     * @return Project
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
     * Set admin.
     *
     * @param \AppBundle\Entity\User|null $admin
     *
     * @return Project
     */
    public function setAdmin(\AppBundle\Entity\User $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Add task.
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return Project
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
     * Add sprint.
     *
     * @param \AppBundle\Entity\Sprint $sprint
     *
     * @return Project
     */
    public function addSprint(\AppBundle\Entity\Sprint $sprint)
    {
        $this->sprints[] = $sprint;

        return $this;
    }

    /**
     * Remove sprint.
     *
     * @param \AppBundle\Entity\Sprint $sprint
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeSprint(\AppBundle\Entity\Sprint $sprint)
    {
        return $this->sprints->removeElement($sprint);
    }

    /**
     * Add user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Project
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
}
