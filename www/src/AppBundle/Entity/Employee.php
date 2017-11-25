<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\employeeRepository")
 */
class Employee
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
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var EmployeePosition
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EmployeePosition", inversedBy="id")
     * @ORM\JoinColumn(name="employeePosition", referencedColumnName="id")
     */
    private $employeePosition;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     *
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $photo;

    /**
     * @var float
     *
     * @ORM\Column(name="salaryRate", type="float", length=10)
     */
    private $salaryRate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="employmentDate", type="date")
     */
    private $employmentDate;

    /**
     * @var float
     *
     * @ORM\Column(name="total_salary", type="float", nullable=true)
     */
    private $totalSalary;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="salary_last_calculated_at", type="date", nullable=true)
     */
    private $salaryLastCalculatedAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkOffDays", mappedBy="employee")
     */
    private $workOffDays;


    /**
     * Employee constructor.
     */
    public function __construct()
    {
        $this->workOffDays = new ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Employee
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Employee
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set employeePosition
     *
     * @param EmployeePosition $employeePosition
     *
     * @return Employee
     */
    public function setEmployeePosition(EmployeePosition $employeePosition)
    {
        $this->employeePosition = $employeePosition;

        return $this;
    }

    /**
     * Get employeePosition
     *
     * @return EmployeePosition
     */
    public function getEmployeePosition()
    {
        return $this->employeePosition;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Employee
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set salaryRate
     *
     * @param float $salaryRate
     *
     * @return Employee
     */
    public function setSalaryRate($salaryRate)
    {
        $this->salaryRate = $salaryRate;

        return $this;
    }

    /**
     * Get salaryRate
     *
     * @return float
     */
    public function getSalaryRate()
    {
        return $this->salaryRate;
    }

    /**
     * Set employmentDate
     *
     * @param \DateTime $employmentDate
     *
     * @return Employee
     */
    public function setEmploymentDate($employmentDate)
    {
        $this->employmentDate = $employmentDate;

        return $this;
    }

    /**
     * Get employmentDate
     *
     * @return \DateTime
     */
    public function getEmploymentDate()
    {
        return $this->employmentDate;
    }

    /**
     * @return ArrayCollection
     */
    public function getWorkOffDays()
    {
        return $this->workOffDays;
    }

    /**
     * @return float
     */
    public function getTotalSalary()
    {
        return $this->totalSalary;
    }

    /**
     * @param float $totalSalary
     * @return Employee
     */
    public function setTotalSalary($totalSalary)
    {
        $this->totalSalary = $totalSalary;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSalaryLastCalculatedAt()
    {
        return $this->salaryLastCalculatedAt;
    }

    /**
     * @param \DateTime $salaryLastCalculatedAt
     * @return Employee
     */
    public function setSalaryLastCalculatedAt($salaryLastCalculatedAt)
    {
        $this->salaryLastCalculatedAt = $salaryLastCalculatedAt;
        return $this;
    }
}

