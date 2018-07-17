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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="projects")
     */
    private $admin;

    /**
     * @ORM\Column(type="double")
     */
    private $price;

    /**
     * @ORM\Column(type="double")
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
     * @ORM\Column(type="double")
     */
    private $hourPool;

    /**
     * @ORM\Column(type="double")
     */
    private $hourSpend;

    /**
     * @ORM\Column(type="timestamp")
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

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->sprints = new ArrayCollection();
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getSprints(): Collection
    {
        return $this->sprints;
    }
}