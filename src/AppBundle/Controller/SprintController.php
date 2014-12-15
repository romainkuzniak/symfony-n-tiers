<?php

namespace AppBundle\Controller;

use AppBundle\Exception\SprintAlreadyClosedException;
use AppBundle\Exception\SprintNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Romain Kuzniak <romain.kuzniak@turn-it-up.org>
 */
class SprintController extends Controller
{
    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function closeAction($id)
    {
        try {
            $report = $this->get('service.sprint')->closeSprint($id);
            $this->getDoctrine()->getManager()->flush();

            return $this->render(
                'AppBundle:Sprint:close.html.twig',
                array(
                    'id'                  => $id,
                    'closedIssuesCount'   => $report['closedIssuesCount'],
                    'averageClosedIssues' => $report['averageClosedIssues']
                )
            );
        } catch (SprintAlreadyClosedException $sace) {
            $this->get('session')->getFlashBag()->add('error', 'Sprint already closed');

            return $this->redirect($this->generateUrl('show_sprint', array('id' => $id)));
        } catch (SprintNotFoundException $snfe) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        try {
            $sprint = $this->get('service.sprint')->get($id);

            return $this->render('AppBundle:Sprint:show.html.twig', array('sprint' => $sprint));
        } catch (SprintNotFoundException $snfe) {
            throw new NotFoundHttpException();
        }
    }
}
