<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\MainController;
use AppBundle\Entity\Employee;
use AppBundle\Entity\WorkOffDays;
use AppBundle\Form\EmployeeFormType;
use AppBundle\Form\EmployeePositionFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EmployeeController
 * @package AppBundle\Controller
 *
 * @Route("/admin")
 */
class EmployeeController extends MainController
{
    /**
     * @Route("/dashboard", name="admin_dashboard")
     */
    public function adminDashboardAction()
    {
        $data['jobs'] = $this->getJobsData();
        $data['employee'] = $this->getRepo('Employee')->findAll();
        $data['workOff_days'] = $this->getRepo('WorkOffDays')->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @Route("/employees", name="admin_employees")
     */
    public function adminEmployeeSectionAction()
    {
        $data['employee'] = $this->getRepo('Employee')->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @Route("/jobs", name="admin_jobs")
     */
    public function adminJobsSectionAction()
    {
        $data['jobs'] = $this->getJobsData();

        return $this->render('admin/dashboard.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @Route("/days-off", name="admin_days_off")
     */
    public function adminDaysOffSectionAction()
    {
        $data['workOff_days'] = $this->getRepo('WorkOffDays')->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'data' => $data,
        ]);
    }

    /**
     * @param $request Request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/employee/add", name="add_new_employee")
     */
    public function addNewEmployeeAction(Request $request)
    {
        $form = $this->createForm(EmployeeFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            $this->addFlash('success', 'Cпасибо, данные сохранены');

            return $this->redirectToRoute('homepage');
        }
        return $this->render('admin/add_employee.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/employee/edit/{id}", name="edit_employee_info")
     */
    public function editEmployeeInfoAction(Request $request, $id)
    {
        /**
         * @var $employee Employee
         */
        $employee = $this->getRepo('Employee')->find($id);
        if (null == $employee) {
            throw $this->createNotFoundException('Employee not found');
        }

        $form = $this->createForm(EmployeeFormType::class, $employee);
        $form->handleRequest($request);

        // additional info to editing employer
        $weekends = $this->getParameter('working_days_per_week') + 1;
        $work_off_days = json_encode($employee->getWorkOffDays()->getValues());
        $now = new \DateTime('now');
        $month_count = $now->diff($employee->getEmploymentDate())->m;

        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);

            if ($request->request->has('work_off_days')) {
                $work_off_days = explode(',',$request->request->get('work_off_days'));
                $hire_day = $employee->getEmploymentDate()->getTimestamp();
                foreach ($work_off_days as $day) {
                    $date = new \DateTime($day);
                    $day_off_week = date('N', strtotime($date->format('Y/m/d')));

                    if ($date->getTimestamp() > $hire_day
                        && $date->getTimestamp() < $now->getTimestamp()
                        && $day_off_week < $weekends) {

                        $workOffDay = new WorkOffDays();
                        $workOffDay->setEmployee($employee)->setDate($date);
                        $em->persist($workOffDay);
                    }
                }
            }

            $em->flush();

            $this->addFlash('success', 'Cпасибо, данные сохранены!');

            return $this->redirectToRoute('homepage');
        }
        return $this->render(':admin:edit_employee.html.twig', [
            'form' => $form->createView(),
            'weekends' => $weekends,
            'work_off_days' => $work_off_days,
            'month_count' => $month_count,
            'employee_id' => $employee->getId()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/job/add", name="add_new_position")
     */
    public function addJobsAction(Request $request)
    {
        $form = $this->createForm(EmployeePositionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employee_position = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee_position);
            $em->flush();

            $this->addFlash('success', 'Cпасибо, данные сохранены');

            return $this->redirectToRoute('homepage');
        }
        return $this->render('admin/add_job.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $job
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/job/edit/{job}", name="edit_employee_position")
     */
    public function editJobAction(Request $request, $job)
    {
        $job = $this->getRepo('EmployeePosition')->findOneBy(['urlPath' => $job]);

        if (null == $job) {
            throw $this->createNotFoundException('Job not found');
        }

        $form = $this->createForm(EmployeePositionFormType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employee_position = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee_position);
            $em->flush();

            $this->addFlash('success', 'Cпасибо, данные сохранены');

            return $this->redirectToRoute('homepage');
        }
        return $this->render('admin/add_job.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function getWeekendDays()
    {
        return $this->getParameter('working_days_per_week') + 1;
    }
}
