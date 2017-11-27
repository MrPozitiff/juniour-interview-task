<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmployeePosition;
use AppBundle\Repository\EmployeeRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HelpersController
 * @package AppBundle\Controller
 *
 */
class HelpersController extends Controller
{
    private $results_per_page = 5;

    // Helpers

    /**
     * @param $entity string
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepo($entity)
    {
        return $this->getDoctrine()->getManager()->getRepository("AppBundle:".$entity);
    }

    /**
     * @param $page int
     * @param $job null | EmployeePosition
     * @return array
     */
    public function getEmployeePaginatorData($page, $job = null)
    {
        /**
         * @var $repo EmployeeRepository
         */
        $repo = $this->getRepo('Employee');
        if (is_null($job)) {
            $paginator = $repo->getAllEmployees($page, $this->results_per_page);
        } else {
            $paginator = $repo->getAllEmployeesByJob($job, $page, $this->results_per_page);
        }

        /**
         * @var $paginator Paginator
         */
        $data['employee'] = $paginator->getIterator();
        $data['maxPages'] = ceil($paginator->count() / $this->results_per_page);
        $data['thisPage'] = $page;

        return $data;
    }

    /**
     * @return array
     */
    public function getJobsData()
    {
        return $this->getRepo('EmployeePosition')->findAll();
    }
}
