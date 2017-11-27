<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkOffDays
 *
 * @ORM\Table(name="work_off_days")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkOffDaysRepository")
 */
class WorkOffDays
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Employee", inversedBy="workOffDays")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $employee;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_checked", type="boolean", nullable=true)
     */
    private $isChecked;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return WorkOffDays
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set employee
     *
     * @param string $employee
     *
     * @return WorkOffDays
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return string
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @return bool
     */
    public function isChecked()
    {
        return $this->isChecked;
    }

    /**
     * @param bool $isChecked
     * @return WorkOffDays
     */
    public function setIsChecked($isChecked)
    {
        $this->isChecked = $isChecked;
        return $this;
    }

    public function __toString()
    {
        return $this->getDate()->format('Y-m-d');
    }
}

