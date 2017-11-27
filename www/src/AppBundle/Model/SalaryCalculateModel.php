<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 025 25.11.17
 * Time: 17:21
 */

namespace AppBundle\Model;


use AppBundle\Entity\Employee;
use AppBundle\Entity\WorkOffDays;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints\DateTime;

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
     * @var \AppBundle\Repository\WorkOffDaysRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $daysOffRepo;

    /**
     * @var \DateTime
     */
    private $joinDate;

    /**
     * @var int
     */
    private $workHoursPerDay;

    /**
     * @var integer
     */
    private $workDaysPerWeek;

    /**
     * @var \DateTime
     */
    private $from_date;

    /**
     * @var \DateTime
     */
    private $to_date;

    /**
     * SalaryCalculateModel constructor.
     * @param $workHoursPerDay integer
     * @param $workDaysPerWeek integer
     * @param EntityManagerInterface $em
     */
    public function __construct($workHoursPerDay, $workDaysPerWeek,EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->daysOffRepo = $this->em
            ->getRepository('AppBundle:WorkOffDays');
        $this->workHoursPerDay = $workHoursPerDay;
        $this->workDaysPerWeek = $workDaysPerWeek;
    }

    /**
     * @param Employee $employee
     * @return $this
     */
    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;
        $this->joinDate = $employee->getEmploymentDate();
        return $this;
    }

    /**
     * @return float
     */
    public function calculateTotalSalary()
    {
        $isValid = $this->daysOffRepo
            ->isCheckedDaysValid($this->employee, $this->employee->getSalaryLastCalculatedAt());

        if (is_null($this->employee->getTotalSalary())
            || is_null($this->employee->getSalaryLastCalculatedAt())
            || !$isValid) {

            $totalSalary = $this->calculateSalaryForPeriod($this->joinDate);
        } else {
            $salary = $this->calculateSalaryForPeriod($this->employee->getSalaryLastCalculatedAt());
            $totalSalary = $salary + $this->employee->getTotalSalary();
        }

        $this->employee
            ->setTotalSalary($totalSalary)
            ->setSalaryLastCalculatedAt(new \DateTime('now'));

        $this->em->persist($this->employee);
        $this->em->flush();

        return $totalSalary;
    }

    /**
     * @param \DateTime | string $from
     * @param \DateTime | string $to
     * @return float
     */
    public function calculateSalaryForPeriod($from, $to = 'yesterday')
    {
        $worked_days = $this->calculateWorkedDays($from, $to);
        return $worked_days * $this->employee->getSalaryRate() * $this->workHoursPerDay;
    }

    /**
     * @param \DateTime | string $from
     * @param \DateTime | string $to
     * @return int
     */
    public function calculateWorkedDays($from, $to = 'yesterday')
    {
        if (!$this->isValidDateFormat($from, $to)) {
            throw new Exception('Unrecognised date format!');
        }

        $from = $this->from_date;
        $to = $this->to_date;

        $worked_days = 0;

        while (($day_of_week = date('N', strtotime($from->format('Y/m/d')))) != 1) {
            $from->modify('+1 day');
            if ($day_of_week >= 6) {
                continue;
            }
            $worked_days++;
        }

        while (($day_of_week = date('N', strtotime($to->format('Y/m/d')))) != 1) {
            $to->modify('-1 day');
            if ($day_of_week >= 6) {
                continue;
            }
            $worked_days++;
        }

        $days_off_count = $this->employee->getWorkOffDaysCount(); // We believe that it doesn't contain weekend
        $weeks = ($from->diff($to)->days)/7;
        $total = $worked_days + ($weeks * $this->workDaysPerWeek) - $days_off_count + 1;

        // Old non productive way
        //
        //$worked_days = 0;
        //$key = 0;
        //$to = $to->getTimestamp();
        //
        //do {
        //    $day_of_week = date('N', strtotime($from->format('Y/m/d')));
        //    if ($day_of_week >= 6) {
        //        continue;
        //    }
        //    /**
        //     * @var $dayOff WorkOffDays
        //     */
        //    if (isset($this->daysOff[$key])) {
        //        $dayOff = $this->daysOff[$key];
        //        if (strtotime($dayOff->getDate()->format('Y/m/d')) == strtotime($from->format('Y/m/d'))) {
        //            $key++;
        //            continue;
        //        }
        //    }
        //
        //    $worked_days++;
        //    $from->modify('+1 day');
        //
        //} while ($from->getTimestamp() < $to);

        return $total;
    }

    /**
     * @param $from \DateTime | string
     * @param $to \DateTime | string
     * @return bool
     */
    private function isValidDateFormat($from, $to)
    {
        $this->from_date = $from;
        $this->to_date = $to;
        if (!is_object($from)) {
            $this->from_date = new \DateTime($from);
        }
        if (!is_object($to)) {
            $this->to_date = new \DateTime($to);
        }
        $from = $this->from_date->getTimestamp();
        $to = $this->to_date->getTimestamp();
        $join_date_unix = strtotime($this->joinDate->format('Y/m/d'));
        $now_unix = time();
        if ($from < $join_date_unix
            || $to > $now_unix
            || $from > $to) {
            return false;
        }
        return true;
    }
}