<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 025 25.11.17
 * Time: 17:21
 */

namespace AppBundle\Model;


use AppBundle\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;

class SalaryCalculateModel
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Employee
     */
    private $employee;

    /**
     * @var array
     */
    private $daysOff;

    /**
     * @var \DateTime
     */
    private $joinDate;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;
        $this->joinDate = $employee->getEmploymentDate();
    }

    public function calculateTotalSalary()
    {
        if (is_null($this->employee->getTotalSalary()) || is_null($this->employee->getSalaryLastCalculatedAt())) {
            $this->calculateSalaryForPeriod($this->joinDate);
        } else {
            $this->daysOff = $this->em
                ->getRepository('AppBundle:WorkOffDays')
                ->findBy(['employee' => $this->employee, 'isChecked' => null]);
        }
    }

    public function calculateSalaryForPeriod($from, $to = 'now')
    {

    }

    private function isValidDateFormat($date)
    {
        $unix_time = strtotime($date);
        if (false === $unix_time) {
            return false;
        }

        $join_date_unix = strtotime($this->joinDate);
        $now_unix = time();
        if ($unix_time < $join_date_unix || $unix_time > $now_unix) {
            return false;
        }

        return true;
    }

    private function lastCalculationDate($date)
    {

    }
}