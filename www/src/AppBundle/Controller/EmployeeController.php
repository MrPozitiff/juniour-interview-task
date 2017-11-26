<?php

namespace AppBundle\Controller;

use AppBundle\Form\EmployeeFormType;
use AppBundle\Form\EmployeePositionFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EmployeeController
 * @package AppBundle\Controller
 *
 * @Route("/admin")
 */
class EmployeeController extends Controller
{
    /**
     * @param $request Request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/add", name="add_new_employee")
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

            $this->addFlash('success', 'Cпасибо, данные сохранены, вы можете их отредактировать или просмотреть список абитуриентов');

            return $this->redirectToRoute('homepage');
        }
        return $this->render('admin/add_employee.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/add/job", name="add_employee_position")
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

            $this->addFlash('success', 'Cпасибо, данные сохранены, вы можете их отредактировать или просмотреть список абитуриентов');

            return $this->redirectToRoute('homepage');
        }
        return $this->render('admin/add_employee.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
