<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Repository\EmployeePositionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package AppBundle\Controller
 */
class MainController extends HelpersController
{
    /**
     * @param $page int
     * @param $json boolean
     * @return JsonResponse | Response
     *
     * @Route("/", name="homepage")
     */
    public function indexAction($page = 1, $json = false)
    {
        $data = $this->getEmployeePaginatorData($page);

        if ($json) {
            return $this->json($data);
        }

        $data['jobs'] = $this->getJobsData();

        return $this->render('default/index.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @param $page int
     * @return Response
     *
     * @Route("/view/{page}", name="home_paginator")
     */
    public function indexPaginatorAction($page)
    {
        return $this->indexAction($page);
    }

    /**
     * @param $id
     * @param bool $json
     * @return JsonResponse|Response
     *
     * @Route("/employee/{id}", name="employee_info")
     */
   public function viewUserAction($id, $json = false)
   {
       /**
        * @var $employee Employee
        */
       $employee = $this->getRepo('Employee')->findOneBy(['id' => $id]);
       $data['employee'] = $employee;
       $now = new \DateTime('now');

       $data['month_count'] = $now->diff($employee->getEmploymentDate())->m;
       $data['weekends'] = $this->getParameter('working_days_per_week') + 1;

       if (is_null($data['employee'])) {
           if ($json) {
               return $this->json(['error' => 'Date not found']);
           }
           throw $this->createNotFoundException('Employee not found!');
       }

       if ($json) {
           return $this->render(':main/blocks:modal_user_info.html.twig', [
              'employee' => $data['employee'],
           ]);
       }

       $data['jobs'] = $this->getJobsData();

       return $this->render('main/employee_info.html.twig', [
           'data' => $data,
       ]);
   }

    /**
     * @param $job string
     * @param $page int
     * @param $json boolean
     * @return JsonResponse | Response
     *
     * @Route("/{job}/{page}", name="view_by_job")
     */
    public function viewByJobAction($job, $page = 1, $json = false)
    {
        /**
         * @var $job_repo EmployeePositionRepository
         */
        $job_repo = $this->getRepo('EmployeePosition');
        $data['current_job'] = $job_repo->findOneBy(['urlPath' => $job]);

        if (is_null($data['current_job'])) {
            if ($json) {
                return $this->json(['error' => $job.' not found']);
            }
            throw $this->createNotFoundException($job.' page not found:(');
        }

        $data = array_merge(
            $data,
            $this->getEmployeePaginatorData($page, $data['current_job'])
        );

        if ($json) {
            return $this->json($data);
        }

        $data['jobs'] = $job_repo->findAll();

        return $this->render('default/index.html.twig', [
            'data' => $data,
        ]);
    }
}
