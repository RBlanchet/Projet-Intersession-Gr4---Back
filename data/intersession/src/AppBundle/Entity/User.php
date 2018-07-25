<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/07/2018
 * Time: 09:21
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="users_email_unique",columns={"email"})}
 * )
 */
class User implements UserInterface
{

    const MATCH_VALUE_THRESHOLD = 25;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->protectedRoles = new ArrayCollection();
        $this->meetings = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    protected $plainPassword;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Project", mappedBy="users")
     */
    private $projects;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Task", inversedBy="users")
     * @ORM\JoinTable(name="users_tasks", joinColumns={
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)})
     */
    private $tasks;

    /**
     *  @ORM\OneToMany(targetEntity="AppBundle\Entity\Role", mappedBy="user")
     */

    private $protectedRoles;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Meeting", mappedBy="users")
     */
    private $meetings;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Job")
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }


    /**
     * @param $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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

    /**
     * @return ArrayCollection
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param ArrayCollection $jobs
     */
    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * @return Collection
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * @return Collection
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }


    /**
     * @return Collection
     */
    public function getProtectedRoles(): Collection
    {

        return $this->protectedRoles;

    }

    /**
     * @return Collection
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // Suppression des données sensibles
        $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
}