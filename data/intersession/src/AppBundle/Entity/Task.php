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

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }
    public function addUser(User $user)
    {
        $user->addTask($this);
        $this->users[] = $user;
    }

}