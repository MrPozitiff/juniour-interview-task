<?php

namespace AppBundle\Controller\API;

use AppBundle\Controller\MainController;
use AppBundle\Entity\Employee;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EndlessPaginationController
 * @package AppBundle\Controller
 *
 * @Route("/api")
 * @Method(methods={"POST"})
 */
class ApiController extends MainController
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/pagination/main", name="main_pagination_api")
     */
    public function homePaginationAction(Request $request)
    {
        $page = $request->request->get('page');

        return $this->indexAction($page, true);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/pagination/job", name="job_pagination_api")
     */
    public function jobPaginationAction(Request $request)
    {
        $job = $request->request->get('job');
        $page = $request->request->get('page');

        return $this->viewByJobAction($job, $page, true);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/employee/info", name="employee_info_api")
     */
    public function getUserInfoAction(Request $request)
    {
        $employee_id = $request->request->get('id');

        return $this->viewUserAction($employee_id, true);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/employee/calculate", name="salary_calc_api")
     */
    public function calculateAndGetTotalSalaryAction(Request $request)
    {
        $employee_id = $request->request->get('id');
        /**
         * @var $employee Employee
         */
        $employee = $this->getRepo('Employee')->find($employee_id);

        if (is_null($employee)) {
            return $this->json(['error' => 'employee does not exist!']);
        }

        $salaryCalc = $this->container->get('salary.calculate.model');
        $total = $salaryCalc->setEmployee($employee)->calculateTotalSalary();
        $calc_at = new \DateTime('now');

        return $this->json([
            'total' => $total,
            'calc_at' => $calc_at->format('Y/m/d')
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/employee/work-off", name="get_work_off_days_api")
     */
    public function getEmployeeWorkOffDaysAction(Request $request)
    {
        $employee_id = $request->request->get('id');
        /**
         * @var $employee Employee
         */
        $employee = $this->getRepo('Employee')->find($employee_id);

        if (is_null($employee)) {
            return $this->json(['error' => 'employee does not exist!'], 404);
        }

        $days_off = $employee->getWorkOffDays()->getValues();
        $arr = [];
        foreach ($days_off as $day) {
            $arr[] = [
                'date' => (string)$day,
                'badge' => true,
                'classname' => 'badge-event'
            ];
        }

        $salaryCalc = $this->container->get('salary.calculate.model');
        $total = $salaryCalc->setEmployee($employee)->calculateTotalSalary();
        $calc_at = new \DateTime('now');

        return $this->json(['response' => $arr]);
    }
}
