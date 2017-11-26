<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * employeePosition
 *
 * @ORM\Table(name="employee_position")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeePositionRepository")
 */
class EmployeePosition
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url_path", type="string", length=255)
     */
    private $urlPath;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Employee", mappedBy="employeePosition")
     */
    private $employees;


    /**
     * EmployeePosition constructor.
     */
    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return EmployeePosition
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return ArrayCollection
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * @return string
     */
    public function getUrlPath()
    {
        return $this->urlPath;
    }

    /**
     * @param string $urlPath
     * @return EmployeePosition
     */
    public function setUrlPath($urlPath)
    {
        $this->urlPath = $urlPath;
        return $this;
    }

    /**
     * @return integer
     */
    public function getEmployeesCount()
    {
        return count($this->employees);
    }
}

