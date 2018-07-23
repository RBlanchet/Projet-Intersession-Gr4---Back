<?php // src/AppBundle/Entity/TaskStatus.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tasks_status",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="status_type_task", columns={"title", "task_id"})}
 *     )
 */
class TaskStatus {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\OneToOne(targetEntity="Task", inversedBy="status")
     */
    protected $task;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return TaskStatus
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set task
     *
     * @param \AppBundle\Entity\Task $task
     *
     * @return TaskStatus
     */
    public function setTask(\AppBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \AppBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }
}
