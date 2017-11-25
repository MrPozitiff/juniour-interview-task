<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Request;

/**
 * Class EndlessPaginationController
 * @package AppBundle\Controller
 *
 * @Route("/api/pagination")
 * @Method(methods={"POST"})
 */
class EndlessPaginationController extends MainController
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/main", name="main_pagination_api")
     */
    public function homePaginationAction(Request $request)
    {
        $page = $request->request->get('page');
        return $this->indexAction($page, true);
    }

    public function jobPaginationAction(Request $request)
    {
        $job = $request->request->get('job');
        $page = $request->request->get('page');
        $this->viewByJobAction($job, $page, true);
    }
}
