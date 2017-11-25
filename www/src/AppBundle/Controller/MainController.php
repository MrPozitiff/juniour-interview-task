<?php

namespace AppBundle\Controller;

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
        $data['jobs'] = $this->getJobsData();

        $data = array_merge(
            $data,
            $this->getEmployeePaginatorData($page)
        );

        if ($json) {
            return $this->json($data);
        }

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

        $data['jobs'] = $job_repo->findAll();

        $data = array_merge(
            $data,
            $this->getEmployeePaginatorData($page, $data['current_job'])
        );

        if ($json) {
            return $this->json($data);
        }

        return $this->render('default/index.html.twig', [
            'data' => $data,
        ]);
    }
}
