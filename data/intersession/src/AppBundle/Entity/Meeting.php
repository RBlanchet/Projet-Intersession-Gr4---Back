<?php
// src/AppBundle/Entity/Meeting.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $organiser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="float")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="meetings")
     */
    private $project;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="meetings")
     * @ORM\JoinTable(name="users_meetings")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    public function jsonSerialize()
    {
        return array(
            'project' . $this->id => array(
                'id'            => $this->id,
                'organiser'     => $this->organiser,
                'name'          => $this->name,
                'project'       => $this->project,
                'location'      => $this->location,
                'description'   => $this->description,
                'timeSpend'     => $this->timeSpend,
                'dateStart'     => $this->dateStart,
                'dateEnd'       => $this->dateEnd,
                'isActive'      => $this->active,
            )
        );
    }

    public function getRelations()
    {
        return array(
            'users'         => $this->getUsers(),
        );
    }
    public function getUsers()
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
     * @return Meeting
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
     * @return Meeting
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
     * Set organiser.
     *
     * @param string $organiser
     *
     * @return Meeting
     */
    public function setOrganiser($organiser)
    {
        $this->organiser = $organiser;

        return $this;
    }

    /**
     * Get organiser.
     *
     * @return string
     */
    public function getOrganiser()
    {
        return $this->organiser;
    }

    /**
     * Set location.
     *
     * @param string $location
     *
     * @return Meeting
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set timeSpend.
     *
     * @param float $timeSpend
     *
     * @return Meeting
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
     * Set dateStart.
     *
     * @param \DateTime $dateStart
     *
     * @return Meeting
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
     * @return Meeting
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Meeting
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
     * @return Meeting
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
     * @return Meeting
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
     * Add user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Meeting
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
     * Set project.
     *
     * @param \AppBundle\Entity\Project|null $project
     *
     * @return Meeting
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
}
